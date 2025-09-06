<?php
// lib/DB.php
require_once __DIR__ . '/../database.php';

final class DB {
    private static ?PDO $conn = null;

    public static function conn(): PDO {
        if (self::$conn === null) {
            self::$conn = db();
        }
        return self::$conn;
    }
}
