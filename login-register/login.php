<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Library_Project/database.php';
$pdo = db();

/** @var PDO $pdo */
$pdo = db();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read inputs safely
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validations
    if ($email === '' || $password === '') {
        $errors[] = "Email și parola sunt obligatorii.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email invalid.";
    }

    if (empty($errors)) {
        try {
            // Fetch user by email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            // DEMO RULE: accept only password 'test123'
            // (For production: use password_hash / password_verify)
            if ($user && $password === 'test123') {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect by role
                header('Location: ' . ($user['role'] === 'admin'
                    ? '../users/index.php'
                    : '../books/index_books.php'));
                exit;
            } else {
                $errors[] = "Email sau parolă incorecte.";
            }
        } catch (PDOException $e) {
            $errors[] = "Eroare de bază de date: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"/>
</head>
<body>
  <div class="container my-5" style="max-width:480px;">
    <h2 class="mb-4">Login</h2>

    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $err): ?>
          <div><?php echo htmlspecialchars($err); ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Parolă</label>
        <input type="password" class="form-control" name="password" required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Autentificare</button>
    </form>

    <p class="text-muted mt-3" style="font-size:0.9rem;">
      (Pentru demo: parola acceptată este <code>test123</code>)
    </p>
  </div>
</body>
</html>
