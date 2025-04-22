<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Check if the ID is passed in the URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Delete the kitchen stock item from the database
    $stmt = $pdo->prepare("DELETE FROM kitchen_stocks WHERE id = :id");
    $stmt->bindParam(':id', $item_id);
    $stmt->execute();

    // Redirect back to the kitchen stocks page after deleting
    header("Location: kitchen.php");
    exit;
} else {
    die("Invalid request.");
}
?>
