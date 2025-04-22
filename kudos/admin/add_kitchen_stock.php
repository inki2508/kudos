<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Check if the input values are valid
    if (empty($item_name) || empty($quantity) || $quantity <= 0) {
        $error_message = "Please enter a valid item name and quantity.";
    } else {
        try {
            // Insert the new stock item into the kitchen_stocks table
            $stmt = $pdo->prepare("INSERT INTO kitchen_stocks (item_name, quantity) VALUES (:item_name, :quantity)");
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();

            // Redirect to the kitchen stocks page after adding the item
            header("Location: kitchen.php");
            exit;
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Kitchen Stock - Restaurant Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center text-white">RestoInventory</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="index.php">Home</a></li>
        <li class="nav-item"><a href="bodega1.php">Bodega 1</a></li>
        <li class="nav-item"><a href="bodega2.php">Bodega 2</a></li>
        <li class="nav-item"><a href="kitchen.php">Kitchen Stocks</a></li>
        <li class="nav-item"><a href="office.php">Office Stocks</a></li>
        <li class="nav-item"><a href="counter.php">Counter Stocks</a></li>
        <li class="nav-item"><a href="store.php">Store Stocks</a></li>
        <li class="nav-item"><a href="logout.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Add Kitchen Stock</h2>

    <!-- Display error message if any -->
    <?php if (isset($error_message)) { ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php } ?>

    <div class="form-container">
        <h5>Add New Kitchen Stock Item</h5>
        <form action="add_kitchen_stock.php" method="POST">
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
