<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

// (Devde açık, prod'da kapat)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Formda yazılan description'ı sayfa yenilenince kaybetmemek için:
$description = trim($_POST['description'] ?? '');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"] ?? '');
    $author = trim($_POST["author"] ?? '');
    $genre = trim($_POST["genre"] ?? '');
    $published_year = trim($_POST["published_year"] ?? '');
    $description = trim($_POST["description"] ?? '');

    // --- Validations ---
    if ($title === '' || $author === '' || $genre === '' || $published_year === '') {
        $_SESSION['message'] = "All fields are required.";
        header("Location: create_book.php");
        exit();
    }

    if (!is_numeric($published_year) || (int)$published_year < 1000 || (int)$published_year > (int)date("Y")) {
        $_SESSION['message'] = "Invalid published year.";
        header("Location: create_book.php");
        exit();
    }

    try {
        // 1) Author'u authors tablosuna ekle (varsa IGNORE eder)
        $stmtAuthor = $pdo->prepare("
            INSERT IGNORE INTO authors (name)
            VALUES (?)
        ");
        $stmtAuthor->execute([$author]);

        // 2) Book'u books tablosuna ekle
        $stmt = $pdo->prepare("
            INSERT INTO books (title, author, genre, published_year, description)
            VALUES (:title, :author, :genre, :published_year, :description)
        ");

        $stmt->execute([
            ':title'          => $title,
            ':author'         => $author,
            ':genre'          => $genre,
            ':published_year' => (int)$published_year,
            ':description'    => $description
        ]);

        $_SESSION['message'] = "New book added successfully! ✅";
        header("Location: index_books.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['message'] = "Database error: " . $e->getMessage();
        header("Location: create_book.php");
        exit();
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

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php
            echo htmlspecialchars($_SESSION['message']);
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Author</label>
            <input type="text" class="form-control" name="author" required
                   value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" class="form-control" name="genre" required
                   value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Published Year</label>
            <input type="number" class="form-control" name="published_year" required
                   value="<?= htmlspecialchars($_POST['published_year'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4"
                      placeholder="Short description..."><?= htmlspecialchars($description) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Book</button>
        <a href="index_books.php" class="btn btn-outline-secondary">Back</a>
    </form>
</div>
</body>
</html>
