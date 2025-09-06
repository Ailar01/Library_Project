<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

$hash = password_hash('Admin123!', PASSWORD_DEFAULT);
$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (?,?,?,?)")
    ->execute(['Admin','admin@example.com',$hash,'admin']);
echo 'OK';