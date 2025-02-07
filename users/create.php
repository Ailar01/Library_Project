<?php 
session_start(); // Start session for flash messages
require_once '../users/database.php'; // Use your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);

    // Validation
    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $_SESSION['message'] = "All fields are required.";
        header("Location: create.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email format.";
        header("Location: create.php");
        exit();
    }

    try {
        // Using prepared statements to prevent SQL injection
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, address) VALUES (:name, :email, :phone, :address)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address
        ]);

        $_SESSION['message'] = "New user created successfully!";
        header("Location: create.php");
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
    <title>Create User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Create New User</h2>

        <!-- Display message if available -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); // Clear message after displaying
                ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
