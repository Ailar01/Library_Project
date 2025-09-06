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
    // Delete the book
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['message'] = "Book deleted successfully!";
} catch (PDOException $e) {
    $_SESSION['message'] = "Database error: " . $e->getMessage();
}

// Redirect back to index_books.php
header("Location: index_books.php");
exit();
?>
