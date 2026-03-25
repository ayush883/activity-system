<?php
class Request {
    public static function allow($method) {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            http_response_code(405);
            echo json_encode([
                "error" => "Method Not Allowed. Use $method"
            ]);
            exit;
        }
    }
}