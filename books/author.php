<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

$name = isset($_GET['name']) ? trim($_GET['name']) : '';
if ($name === '') {
    die("Invalid author name.");
}

try {
    $stmt = $pdo->prepare("
        SELECT id, title, genre, published_year
        FROM books
        WHERE author = :author
        ORDER BY published_year DESC
    ");
    $stmt->execute(['author' => $name]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Author</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">

  <a class="btn btn-outline-secondary mb-3" href="index_books.php">← Back to list</a>

  <h3 class="mb-3">Author: <?= htmlspecialchars($name) ?></h3>

  <?php if (!empty($books)): ?>
    <div class="list-group">
      <?php foreach ($books as $b): ?>
        <a class="list-group-item list-group-item-action"
           href="book_details.php?id=<?= urlencode($b['id']) ?>">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?= htmlspecialchars($b['title']) ?></h5>
            <small><?= htmlspecialchars($b['published_year']) ?></small>
          </div>
          <p class="mb-1 text-muted"><?= htmlspecialchars($b['genre']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-muted">No books found for this author.</p>
  <?php endif; ?>

</div>
</body>
</html>
