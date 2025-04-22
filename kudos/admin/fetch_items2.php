<?php
include 'db_connection.php'; // Connect to the database

if (isset($_GET['location'])) {
    $location = $_GET['location'];
    $items = [];

    if ($location === 'store') {
        $stmt = $pdo->prepare("SELECT item_name FROM store_stocks");
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($location === 'kitchen') {
        $stmt = $pdo->prepare("SELECT item_name FROM kitchen_stocks");
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Return items as JSON
    echo json_encode($items);
}
?>
