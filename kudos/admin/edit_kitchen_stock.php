<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Check if the ID is passed in the URL
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Fetch the current details of the item
    $stmt = $pdo->prepare("SELECT * FROM kitchen_stocks WHERE id = :id");
    $stmt->bindParam(':id', $item_id);
    $stmt->execute();
    $item = $stmt->fetch();

    if (!$item) {
        die("Item not found.");
    }

    // Handle the form submission for updating stock
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $item_name = $_POST['item_name'];
        $quantity = $_POST['quantity'];

        // Update the kitchen stock item in the database
        $stmt = $pdo->prepare("UPDATE kitchen_stocks SET item_name = :item_name, quantity = :quantity WHERE id = :id");
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $item_id);
        $stmt->execute();

        // Redirect back to the kitchen stocks page after updating
        header("Location: kitchen.php");
        exit;
    }
} else {
    die("Invalid request.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Kitchen Stock</title>
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
    <h2 class="mb-4">Edit Kitchen Stock</h2>

    <div class="form-container">
        <h5>Edit Stock Item</h5>
        <form action="edit_kitchen_stock.php?id=<?php echo $item['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Stock</button>
        </form>
    </div>

</div>

</body>
</html>
