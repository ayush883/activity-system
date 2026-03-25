<?php
require_once __DIR__ . '/../config/database.php';

class ActivityLogger {
    public static function log($userId, $action, $metadata = []) {
        $db = Database::getConnection();

        $stmt = $db->prepare("INSERT INTO activity_logs 
            (user_id, action, metadata, ip_address, user_agent, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())");

        $stmt->execute([
            $userId,
            $action,
            json_encode($metadata),
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        ]);
    }
}
