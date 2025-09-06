<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();


// Fetch books from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM books ORDER BY created_at DESC");
    $stmt->execute();
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
        <a class="btn btn-primary" href="create_book.php" role="button">Add New Book</a>
        <br><br>

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
                    <?php foreach ($books as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['author']); ?></td>
                            <td><?php echo htmlspecialchars($row['genre']); ?></td>
                            <td><?php echo htmlspecialchars($row['published_year']); ?></td>
                            <td>
                                <a href="edit_book.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete_book.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
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
