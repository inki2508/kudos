<?php
session_start();
include 'db_connection.php';

// Get the source table from the AJAX request
$source = $_GET['source'];

if ($source == 'bodega2') {
    $stmt = $pdo->prepare("SELECT id, item_name FROM bodega2_stocks");
} elseif ($source == 'office') {
    $stmt = $pdo->prepare("SELECT id, item_name FROM office_stocks");
} else {
    $stmt = $pdo->prepare("SELECT id, item_name FROM counter_stocks");
}

$stmt->execute();
$items = $stmt->fetchAll();

echo json_encode($items);
?>
