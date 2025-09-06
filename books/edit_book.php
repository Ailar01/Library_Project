<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();


// Check if 'id' is in the URL and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid book ID!";
    header("Location: index_books.php");
    exit();
}

$id = $_GET['id'];

try {
    // Fetch the book details
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$book) {
        $_SESSION['message'] = "Book not found!";
        header("Location: index_books.php");
        exit();
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $genre = trim($_POST["genre"]);
    $published_year = trim($_POST["published_year"]);

    // Validation
    if (empty($title) || empty($author) || empty($genre) || empty($published_year)) {
        $_SESSION['message'] = "All fields are required.";
        header("Location: edit_book.php?id=".$id);
        exit();
    }

    if (!is_numeric($published_year) || $published_year < 1000 || $published_year > date("Y")) {
        $_SESSION['message'] = "Invalid published year.";
        header("Location: edit_book.php?id=".$id);
        exit();
    }

    try {
        // Update the book
        $stmt = $pdo->prepare("UPDATE books SET title=:title, author=:author, genre=:genre, published_year=:published_year WHERE id=:id");
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':genre' => $genre,
            ':published_year' => $published_year,
            ':id' => $id
        ]);

        $_SESSION['message'] = "Book updated successfully!";
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
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Edit Book</h2>

        <!-- Display session message -->
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
                <input type="text" class="form-control" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Genre</label>
                <input type="text" class="form-control" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Published Year</label>
                <input type="number" class="form-control" name="published_year" value="<?php echo htmlspecialchars($book['published_year']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>
</body>
</html>
