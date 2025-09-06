<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();
$ok = db()->query("SELECT 1")->fetchColumn() == 1;
echo $ok ? "Ping OK" : "Ping FAIL";
