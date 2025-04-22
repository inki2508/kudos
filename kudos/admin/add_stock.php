<?php
session_start();
include 'db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Prepare and execute the SQL statement to insert the new stock
    try {
        $stmt = $pdo->prepare("INSERT INTO bodega1_stocks (item_name, quantity) VALUES (:item_name, :quantity)");
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();

        // Redirect to the Bodega 1 page after adding stock
        header("Location: bodega1.php");
        exit;
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Stock to Bodega 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center text-white">RestoInventory</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="bodega1.php">Bodega 1</a></li>
        <li class="nav-item"><a href="bodega2.php">Bodega 2</a></li>
        <li class="nav-item"><a href="kitchen.php">Kitchen Stocks</a></li>
        <li class="nav-item"><a href="office.php">Office Stocks</a></li>
        <li class="nav-item"><a href="counter.php">Counter Stocks</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" style="margin-left: 270px; padding: 20px;">
    <h2 class="mb-4">Add New Stock to Bodega 1</h2>

    <!-- Add Stock Form -->
    <div class="dashboard-card">
        <form action="add_stock.php" method="POST">
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock</button>
        </form>
    </div>

</div>

</body>
</html>
