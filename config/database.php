<?php
class Database {
    private static $instance = null;

    public static function getConnection() {
        try {
            if (!self::$instance) {
                self::$instance = new PDO(
                    "mysql:host=localhost;dbname=activity_db",
                    "root",
                    "root",
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]
                );
            }
            return self::$instance;

        } catch (PDOException $e) {
            die(json_encode([
                "error" => "Database connection failed"
            ]));
        }
    }
}