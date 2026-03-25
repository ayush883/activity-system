<?php
require_once '../config/database.php';
require_once '../classes/RateLimiter.php';
require_once '../classes/Request.php';

Request::allow('GET');

header('Content-Type: application/json');

try {

    RateLimiter::check($_SERVER['REMOTE_ADDR']);

    // Ensure cache directory exists
    $cacheDir = '../cache';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $cacheFile = $cacheDir . '/top_users.json';

    // Serve from cache (2 min)
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 120) {
        echo file_get_contents($cacheFile);
        exit;
    }

    $db = Database::getConnection();

    $stmt = $db->query("
        SELECT user_id, COUNT(*) as total 
        FROM activity_logs 
        GROUP BY user_id 
        ORDER BY total DESC 
        LIMIT 5
    ");

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        "status" => "success",
        "data" => $result
    ];

    $jsonData = json_encode($response);

    // Save cache
    file_put_contents($cacheFile, $jsonData);

    echo $jsonData;

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}