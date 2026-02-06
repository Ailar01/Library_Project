<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid book");
}

try {
    // 1) Select the current book
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        die("Invalid book");
    }

    // 2) Select other books by the same author (excluding current book)
    $stmt2 = $pdo->prepare("
        SELECT id, title, genre, published_year
        FROM books
        WHERE author = :author AND id <> :id
        ORDER BY published_year DESC, id DESC
    ");
    $stmt2->execute([
        ':author' => $book['author'],
        ':id'     => $id
    ]);
    $otherBooks = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Safe outputs
$title = htmlspecialchars($book['title'] ?? '');
$author = htmlspecialchars($book['author'] ?? '');
$genre = htmlspecialchars($book['genre'] ?? '');
$year = htmlspecialchars((string)($book['published_year'] ?? ''));
$description = trim((string)($book['description'] ?? ''));
$descriptionSafe = $description !== '' ? nl2br(htmlspecialchars($description)) : '<em>No description provided.</em>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Book Details</h2>
        <a class="btn btn-outline-secondary" href="index_books.php">Back to list</a>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="bookTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                    data-bs-target="#details" type="button" role="tab"
                    aria-controls="details" aria-selected="true">
                Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="author-tab" data-bs-toggle="tab"
                    data-bs-target="#authorBooks" type="button" role="tab"
                    aria-controls="authorBooks" aria-selected="false">
                Other books by this author
            </button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 p-4" id="bookTabsContent">

        <!-- TAB 1: DETAILS -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="row g-3">
                <div class="col-md-8">
                    <h3 class="mb-2"><?= $title ?></h3>
                    <div class="mb-2"><strong>Author:</strong> <?= $author ?></div>
                    <div class="mb-2"><strong>Genre:</strong> <?= $genre ?></div>
                    <div class="mb-3"><strong>Published Year:</strong> <?= $year ?></div>

                    <div class="card">
                        <div class="card-header"><strong>Description</strong></div>
                        <div class="card-body">
                            <?= $descriptionSafe ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><strong>Actions</strong></div>
                        <div class="card-body d-grid gap-2">
                            <a class="btn btn-primary" href="edit_book.php?id=<?= urlencode((string)$book['id']) ?>">Edit</a>
                            <a class="btn btn-danger"
                               href="delete_book.php?id=<?= urlencode((string)$book['id']) ?>"
                               onclick="return confirm('Are you sure you want to delete this book?')">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: OTHER BOOKS -->
        <div class="tab-pane fade" id="authorBooks" role="tabpanel" aria-labelledby="author-tab">
            <h4 class="mb-3">Other books by <?= $author ?></h4>

            <?php if (empty($otherBooks)): ?>
                <div class="alert alert-warning mb-0">
                    No other books found for this author.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th style="width: 120px;">Open</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($otherBooks as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['title']) ?></td>
                                <td><?= htmlspecialchars($b['genre']) ?></td>
                                <td><?= htmlspecialchars((string)$b['published_year']) ?></td>
                                <td>
                                    <a class="btn btn-outline-primary btn-sm"
                                       href="book_details.php?id=<?= urlencode((string)$b['id']) ?>">
                                        View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
