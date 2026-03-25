<?php
require_once '../classes/ActivityLogger.php';
require_once '../classes/RateLimiter.php';
require_once '../classes/Request.php';

Request::allow('POST');

header('Content-Type: application/json');

try {

    RateLimiter::check($_SERVER['REMOTE_ADDR']);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['user_id']) || !isset($data['action'])) {
        throw new Exception("Invalid input. user_id and action required");
    }

    ActivityLogger::log(
        $data['user_id'],
        $data['action'],
        $data['metadata'] ?? []
    );

    // Cache invalidation
    $cacheFile = '../cache/top_users.json';
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }

    echo json_encode([
        "status" => "success"
    ]);

} catch (Exception $e) {

    http_response_code(400);

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}