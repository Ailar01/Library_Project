<?php
declare(strict_types=1);
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$loaded=''; foreach ([__DIR__.'/database.php',__DIR__.'/../database.php'] as $p){ if(file_exists($p)){ require_once $p; $loaded=$p; break; } }
$pdo = function_exists('db') ? db() : null; $active='n/a'; $tables=[]; $mysql=''; $php=PHP_VERSION; $err='';
try{ if($pdo instanceof PDO){ $active=(string)($pdo->query('SELECT DATABASE()')->fetchColumn()?:'n/a'); $mysql=(string)$pdo->query('SELECT VERSION()')->fetchColumn(); $tables=$pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);} else { $err='db() not available'; } } catch (Throwable $ex){ $err=$ex->getMessage(); }
?><!doctype html><meta charset="utf-8"><title>Debug</title>
<style>body{font-family:system-ui,Segoe UI,Arial,sans-serif;margin:24px}.badge{display:inline-block;padding:6px 10px;border-radius:8px;font-weight:600}.ok{background:#e6ffed;border:1px solid #34d058;color:#22863a}.warn{background:#fff5f5;border:1px solid #e55353;color:#c82333}pre{background:#0f172a;color:#e5e7eb;padding:12px;border-radius:8px;overflow:auto}table{border-collapse:collapse}td,th{border:1px solid #e5e7eb;padding:6px 10px}</style>
<h1>Active Database: <span class="badge <?= ($active==='library_db'?'ok':'warn') ?>"><?= e($active) ?></span></h1>
<p><strong>PHP:</strong> <?= e($php) ?> | <strong>MySQL:</strong> <?= e($mysql ?: 'n/a') ?></p>
<h2>Loaded database.php</h2>
<pre><?= e($loaded ?: 'not found') ?></pre>
<h2>Tables</h2>
<?php if ($tables): ?><table><thead><tr><th>#</th><th>name</th></tr></thead><tbody>
<?php foreach ($tables as $i=>$t): ?><tr><td><?= $i+1 ?></td><td><?= e((string)$t) ?></td></tr><?php endforeach; ?>
</tbody></table><?php else: ?><p>No tables.</p><?php endif; ?>
<?php if ($err): ?><h2>Error</h2><pre><?= e($err) ?></pre><?php endif; ?>
