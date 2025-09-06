<?php
// tests/db_smoke.php
require __DIR__ . '/../database.php';

try {
    $pdo = db();
    if (!($pdo instanceof PDO)) {
        throw new RuntimeException('db() did not return a PDO instance');
    }

    // Pick a simple query based on driver (Oracle needs DUAL)
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    $sql = in_array($driver, ['oci', 'oci8']) ? 'SELECT 1 FROM DUAL' : 'SELECT 1';

    $stmt = $pdo->query($sql);
    if ($stmt === false) {
        throw new RuntimeException('Simple SELECT 1 query failed');
    }

    echo "DB OK\n";
    exit(0);
} catch (Throwable $e) {
    echo "DB ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
