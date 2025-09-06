<?php
declare(strict_types=1);

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

$active = (string)($pdo->query('SELECT DATABASE()')->fetchColumn() ?: 'n/a');
$mysql  = (string)$pdo->query('SELECT VERSION()')->fetchColumn();
$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>DB Debug</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
 body{font-family:system-ui,Segoe UI,Arial,sans-serif;margin:24px}
 .badge{display:inline-block;padding:6px 10px;border-radius:8px;font-weight:600}
 .ok{background:#e6ffed;border:1px solid #34d058;color:#22863a}
 .warn{background:#fff5f5;border:1px solid #e55353;color:#c82333}
 table{border-collapse:collapse} td,th{border:1px solid #e5e7eb;padding:6px 10px}
</style>
</head>
<body>
<h1>Active Database:
  <span class="badge <?= ($active==='library_db'?'ok':'warn') ?>"><?= e($active) ?></span>
</h1>
<p><strong>MySQL:</strong> <?= e($mysql ?: 'n/a') ?></p>

<h2>Tables</h2>
<?php if ($tables): ?>
<table>
  <thead><tr><th>#</th><th>Name</th></tr></thead>
  <tbody>
  <?php foreach ($tables as $i => $t): ?>
    <tr><td><?= $i+1 ?></td><td><?= e((string)$t) ?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
<p>No tables.</p>
<?php endif; ?>
</body>
</html>
