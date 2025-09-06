<?php
declare(strict_types=1);
session_start();
if (empty($_SESSION['uid'])) {
    header('Location: /Library_Project/login-register/login.php');
    exit;
}
