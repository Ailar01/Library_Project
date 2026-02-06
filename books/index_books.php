<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

// Search value
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if ($search !== '') {
        $stmt = $pdo->prepare("
    SELECT * FROM books
    WHERE title LIKE :q1 OR author LIKE :q2
    ORDER BY created_at DESC
");
$q = '%' . $search . '%';
$stmt->execute([
    ':q1' => $q,
    ':q2' => $q
]);

    } else {
        $stmt = $pdo->prepare("SELECT * FROM books ORDER BY created_at DESC");
        $stmt->execute();
    }

    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Books</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">

    <h2>List of Books</h2>

    <a class="btn btn-primary mb-3" href="create_book.php">Add New Book</a>

    <!-- SEARCH FORM -->
    <form method="get" class="row g-2 align-items-center mb-3">
        <div class="col-auto" style="min-width: 320px;">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by title or author"
                value="<?= htmlspecialchars($search) ?>"
            >
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Search</button>
            <?php if ($search !== ''): ?>
                <a href="index_books.php" class="btn btn-outline-secondary">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Author</th>
            <th>Genre</th>
            <th>Published Year</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>

        <?php if (!empty($books)): ?>
            <?php $i = 1; ?>
            <?php foreach ($books as $row): ?>
                <tr>
                    <td><?= $i++ ?></td>

                    <td>
                        <a href="book_details.php?id=<?= urlencode($row['id']) ?>">
                            <?= htmlspecialchars($row['title']) ?>
                        </a>
                    </td>

                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= htmlspecialchars($row['genre']) ?></td>
                    <td><?= htmlspecialchars($row['published_year']) ?></td>

                    <td>
                        <a href="edit_book.php?id=<?= urlencode($row['id']) ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete_book.php?id=<?= urlencode($row['id']) ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Are you sure you want to delete this book?')">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No books found</td></tr>
        <?php endif; ?>

        </tbody>
    </table>

</div>
</body>
</html>
