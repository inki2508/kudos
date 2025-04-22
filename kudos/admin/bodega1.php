<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Fetch the Bodega 1 stock data
$stmt = $pdo->prepare("SELECT * FROM bodega1_stocks");
$stmt->execute();
$bodega1Stocks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bodega 1 Stocks</title>
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
        .dashboard-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center text-white">RestoInventory</h4>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="index.php">Home</a></li> <!-- Home Page Link -->
        <li class="nav-item"><a href="bodega1.php">Bodega 1</a></li>
        <li class="nav-item"><a href="bodega2.php">Bodega 2</a></li>
        <li class="nav-item"><a href="kitchen.php">Kitchen Stocks</a></li>
        <li class="nav-item"><a href="office.php">Office Stocks</a></li>
        <li class="nav-item"><a href="counter.php">Counter Stocks</a></li>
        <li class="nav-item"><a href="store.php">Store Stocks</a></li> <!-- Store Stocks -->
        <li class="nav-item"><a href="sales_page.php">Sales Page</a></li> <!-- Sales Page Link -->
        <li class="nav-item"><a href="logout.php">Logout</a></li> <!-- Logout -->
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">
    <h2 class="mb-4">Bodega 1 Stocks</h2>

    <!-- Table to Display Bodega 1 Stocks -->
    <div class="dashboard-card">
        <h5 class="text-center">Stocks Available in Bodega 1</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bodega1Stocks as $stock) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stock['id']); ?></td>
                        <td><?php echo htmlspecialchars($stock['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($stock['quantity']); ?></td>
                        <td>
                            <a href="bodega1edit.php?id=<?php echo $stock['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_stock.php?id=<?php echo $stock['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Add New Stock Form -->
    <div class="dashboard-card mt-4">
        <h5>Add New Stock to Bodega 1</h5>
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
