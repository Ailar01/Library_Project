<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();


// Validate 'id' before processing
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid user ID!";
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    // Delete user securely
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $_SESSION['message'] = "User deleted successfully!";
} catch (PDOException $e) {
    $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
}

// Redirect back to index.php
header("Location: index.php");
exit();
?>
