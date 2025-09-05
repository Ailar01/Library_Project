<?php
require_once __DIR__ . '/../database.php';
$ok = db()->query("SELECT 1")->fetchColumn() == 1;
echo $ok ? "Ping OK" : "Ping FAIL";
