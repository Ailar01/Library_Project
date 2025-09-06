<?php
declare(strict_types=1);
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $host = '127.0.0.1'; $port = 3306; $name = 'library_db'; $user = 'root'; $pass = '';
    $opts = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES=>false, PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8mb4"];
    $root = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, $opts);
    $root->exec("CREATE DATABASE IF NOT EXISTS `{$name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4", $user, $pass, $opts);
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users`(`id` INT AUTO_INCREMENT PRIMARY KEY,`name` VARCHAR(255) NOT NULL,`email` VARCHAR(255) NOT NULL UNIQUE,`password` VARCHAR(255) NOT NULL,`phone` VARCHAR(50) NULL,`address` VARCHAR(255) NULL,`role` ENUM('admin','user') DEFAULT 'user',`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `books`(`id` INT AUTO_INCREMENT PRIMARY KEY,`title` VARCHAR(255) NOT NULL,`author` VARCHAR(255) NOT NULL,`genre` VARCHAR(100) NULL,`published_year` INT NULL,`status` ENUM('available','loaned') DEFAULT 'available',`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $pdo->exec("CREATE TABLE IF NOT EXISTS `loans`(`id` INT AUTO_INCREMENT PRIMARY KEY,`user_id` INT NOT NULL,`book_id` INT NOT NULL,`date` DATE NOT NULL,CONSTRAINT `fk_loans_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,CONSTRAINT `fk_loans_book` FOREIGN KEY (`book_id`) REFERENCES `books`(`id`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    return $pdo;
}
