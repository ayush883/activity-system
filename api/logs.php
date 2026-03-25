<?php
require_once '../config/database.php';
require_once '../classes/RateLimiter.php';
require_once '../classes/Request.php';

Request::allow('GET');

header('Content-Type: application/json');

try {

    RateLimiter::check($_SERVER['REMOTE_ADDR']);

    $db = Database::getConnection();

    $where = [];
    $params = [];

    // Filters
    if (!empty($_GET['user_id'])) {
        $where[] = "user_id = ?";
        $params[] = $_GET['user_id'];
    }

    if (!empty($_GET['action'])) {
        $where[] = "action = ?";
        $params[] = $_GET['action'];
    }

    if (!empty($_GET['date_from'])) {
        $where[] = "created_at >= ?";
        $params[] = $_GET['date_from'];
    }

    if (!empty($_GET['date_to'])) {
        $where[] = "created_at <= ?";
        $params[] = $_GET['date_to'];
    }

    // Base query
    $sql = "SELECT * FROM activity_logs";

    if ($where) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    // Pagination
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    // Sorting (optional improvement)
    $sort = (isset($_GET['sort']) && strtoupper($_GET['sort']) === 'ASC') ? 'ASC' : 'DESC';

    $sql .= " ORDER BY created_at $sort LIMIT $limit OFFSET $offset";

    // Execute
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "count" => count($data),
        "data" => $data
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}