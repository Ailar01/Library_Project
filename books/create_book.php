<?php
session_start();

// Adjust this require if your path differs on your machine/server
require_once __DIR__ . '/../lib/DB.php';
$pdo = DB::conn();

// Enable error display for debugging (optional; disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1) Read & sanitize inputs safely
    $title          = trim($_POST['title'] ?? '');
    $author         = trim($_POST['author'] ?? '');
    $genre          = trim($_POST['genre'] ?? '');
    $published_year = trim($_POST['published_year'] ?? '');

    // 2) Validate inputs (collect errors)
    $errors = [];

    // Specific requirement for our PR: Title must not be empty
    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if ($author === '') {
        $errors[] = 'Author is required.';
    }

    if ($genre === '') {
        $errors[] = 'Genre is required.';
    }

    // Year: numeric and reasonable range (1000 .. current year)
    $currentYear = (int)date('Y');
    if ($published_year === '' || !ctype_digit($published_year)) {
        $errors[] = 'Published year must be a number.';
    } else {
        $py = (int)$published_year;
        if ($py < 1000 || $py > $currentYear) {
            $errors[] = 'Published year must be between 1000 and ' . $currentYear . '.';
        }
    }

    // If any validation errors, show them and stop before DB insert
    if (!empty($errors)) {
        $_SESSION['message'] = implode('<br>', array_map('htmlspecialchars', $errors));
        header('Location: create_book.php');
        exit();
    }

    // 3) Insert into database (only when no errors)
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO books (title, author, genre, published_year)
             VALUES (:title, :author, :genre, :published_year)'
        );
        $stmt->execute([
            ':title'          => $title,
            ':author'         => $author,
            ':genre'          => $genre,
            ':published_year' => (int)$published_year,
        ]);

        $_SESSION['message'] = 'New book added successfully!';
        header('Location: index_books.php');
        exit();
    } catch (PDOException $e) {
        // In production, log the error instead of exposing details
        $_SESSION['message'] = 'Database error: ' . htmlspecialchars($e->getMessage());
        header('Location: create_book.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add New Book</title>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>
<body>
<div class="container my-5">
    <h2>Add New Book</h2>

    <!-- Session message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info mt-3">
            <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" class="form-control" name="author" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" class="form-control" name="genre" required />
        </div>
        <div class="mb-3">
            <label class="form-label">Published Year</label>
            <input type="number" class="form-control" name="published_year" required />
        </div>
        <button type="submit" class="btn btn-primary">Add Book</button>
    </form>
</div>
</body>
</html>
