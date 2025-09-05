<?php
// database.php — Central DB bootstrap (PDO)

$DB_HOST = '127.0.0.1';   // localhost da olur ama 127.0.0.1 daha stabil
$DB_PORT = 3306;          // XAMPP MySQL portun farklıysa değiştir (örn. 3307)
$DB_NAME = 'library_db';  // README ve .sql ile uyumlu
$DB_USER = 'root';
$DB_PASS = '';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    // 1) DB yoksa oluşturmak için önce DB seçmeden bağlan
    $pdo = new PDO("mysql:host=$DB_HOST;port=$DB_PORT;charset=utf8mb4", $DB_USER, $DB_PASS, $options);

    // 2) Veritabanını oluştur ve seç
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$DB_NAME`");

    // 3) Temel tablolar (yoksa) — import edeceksen de zarar vermez
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            role ENUM('admin','user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            status ENUM('available','loaned') DEFAULT 'available',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS loans (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            book_id INT NOT NULL,
            date DATE NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
        );
    ");
} catch (PDOException $e) {
    http_response_code(500);
    die("Database bootstrap failed: " . htmlspecialchars($e->getMessage()));
}

// Küçük yardımcı: db() çağrısı ile PDO al
function db(): PDO {
    global $pdo;
    return $pdo;
}
