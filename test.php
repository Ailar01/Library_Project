<?php
require_once __DIR__ . '/database.php';

try {
    $ok = db()->query("SELECT 1")->fetchColumn() == 1;
    echo $ok
        ? "<h2 style='color:green;'>Database connection OK! ✅</h2>"
        : "<h2 style='color:red;'>Test query failed ❌</h2>";
} catch (Throwable $e) {
    echo "<pre style='color:red'>FAILED ❌ " . htmlspecialchars($e->getMessage()) . "</pre>";
}
