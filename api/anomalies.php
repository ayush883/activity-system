<?php
require_once '../config/database.php';
require_once '../classes/RateLimiter.php';
require_once '../classes/Request.php';

Request::allow('GET');

header('Content-Type: application/json');

try{

    RateLimiter::check($_SERVER['REMOTE_ADDR']);

    $db = Database::getConnection();

    $query1 = "SELECT user_id, COUNT(*) as cnt 
            FROM activity_logs 
            WHERE created_at >= NOW() - INTERVAL 1 MINUTE
            GROUP BY user_id HAVING cnt > 10";

    $query2 = "SELECT user_id, COUNT(DISTINCT ip_address) as ips 
            FROM activity_logs 
            WHERE created_at >= NOW() - INTERVAL 5 MINUTE
            GROUP BY user_id HAVING ips > 1";

    $res1 = $db->query($query1)->fetchAll(PDO::FETCH_ASSOC);
    $res2 = $db->query($query2)->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "high_activity" => $res1,
        "multi_ip" => $res2
    ]);
    }catch (Exception $e) {

        http_response_code(500);

        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }