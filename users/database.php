<?php
$host = 'localhost'; // Database server
$dbname = 'library'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Uncomment the following line to confirm successful connection during debugging
    // echo "Database connection successful.";
} catch (PDOException $e) {
    // Display error message if connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
