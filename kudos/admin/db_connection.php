<?php
$host = 'localhost';       // Your database host (usually localhost)
$db   = 'kudos';           // Your database name
$user = 'root';            // Your MySQL username
$pass = '';                // Your MySQL password (empty if none)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: uncomment for debugging connection
    // echo "Connected to kudos database successfully!";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
