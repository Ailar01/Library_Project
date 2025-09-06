<?php
session_start();

if (empty($_SESSION['user_id']) || (($_SESSION['user_role'] ?? '') !== 'admin')) {
    header('Location: /Library_Project/books/index_books.php');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();


// Fetch users from the database
try {
    $stmt = $pdo->prepare("SELECT id, name, email, phone, address, role, created_at FROM users"); // Added role
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>List of Users</h2>
        <a class="btn btn-primary" href="create.php" role="button">New User</a>
        <a class="btn btn-danger float-end" href="../login-register/logout.php">Logout</a> <!-- Logout button -->
        <br><br>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Role</th> <!-- Added Role -->
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td><?php echo htmlspecialchars($row['role']); ?></td> <!-- Display Role -->
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center">No users found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
