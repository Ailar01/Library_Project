<?php
session_start();

// Prevent session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Set session cookie security settings
ini_set('session.cookie_httponly', 1); // Prevent JavaScript from accessing cookies
ini_set('session.cookie_secure', 1); // Ensure cookies are only sent over HTTPS (disable if using localhost)
ini_set('session.use_strict_mode', 1); // Prevent session adoption

// Logout after inactivity (e.g., 10 minutes)
$inactive = 600; // 10 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive)) {
    session_unset();
    session_destroy();
    header("Location: ../login-register/login.php");
    exit();
}
$_SESSION['last_activity'] = time();
?>
