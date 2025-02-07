<?php
session_start();
require_once '../users/database.php'; // Adjust the path if needed

// Enable error display for debugging (optional, remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $genre = trim($_POST["genre"]);
    $published_year = trim($_POST["published_year"]);

    // Validate inputs
    if (empty($title) || empty($author) || empty($genre) || empty($published_year)) {
        $_SESSION['message'] = "All fields are required.";
        header("Location: create_book.php");
        exit();
    }

    // Validate published_year (example range: 1000 to current year)
    if (!is_numeric($published_year) || $published_year < 1000 || $published_year > date("Y")) {
        $_SESSION['message'] = "Invalid published year.";
        header("Location: create_book.php");
        exit();
    }

    try {
        // Insert book into 'books' table
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author, genre, published_year) 
            VALUES (:title, :author, :genre, :published_year)
        ");
        $stmt->execute([
            ':title'          => $title,
            ':author'         => $author,
            ':genre'          => $genre,
            ':published_year' => $published_year
        ]);

        $_SESSION['message'] = "New book added successfully!";
        header("Location: index_books.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Add New Book</h2>

        <!-- Display any session message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']); 
                ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" name="author" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <input type="text" class="form-control" name="genre" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Published Year</label>
                <input type="number" class="form-control" name="published_year" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>
    </div>
</body>
</html>
