<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Handle adding new sales record and adjusting stock
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $sale_date = $_POST['sale_date'];
    $location = $_POST['location']; // store or kitchen

    // Check if enough stock is available for the sale based on location
    if ($location === 'store') {
        // Check the stock in the store
        $stmt = $pdo->prepare("SELECT quantity FROM store_stocks WHERE item_name = ?");
        $stmt->execute([$item_name]);
        $storeStock = $stmt->fetch();

        if ($storeStock && $storeStock['quantity'] >= $quantity) {
            // Reduce stock in the store
            $newQuantity = $storeStock['quantity'] - $quantity;
            $stmt = $pdo->prepare("UPDATE store_stocks SET quantity = ? WHERE item_name = ?");
            $stmt->execute([$newQuantity, $item_name]);

            // Insert into the sales table
            $stmt = $pdo->prepare("INSERT INTO sales (item_name, quantity, sale_date, location) VALUES (?, ?, ?, ?)");
            $stmt->execute([$item_name, $quantity, $sale_date, $location]);

            // Redirect to avoid re-submission
            header('Location: sales_page.php');
            exit;

        } else {
            // If not enough stock, show an error message
            $error = "Not enough stock in store for this item.";
        }
    } elseif ($location === 'kitchen') {
        // Check the stock in the kitchen
        $stmt = $pdo->prepare("SELECT quantity FROM kitchen_stocks WHERE item_name = ?");
        $stmt->execute([$item_name]);
        $kitchenStock = $stmt->fetch();

        if ($kitchenStock && $kitchenStock['quantity'] >= $quantity) {
            // Reduce stock in the kitchen
            $newQuantity = $kitchenStock['quantity'] - $quantity;
            $stmt = $pdo->prepare("UPDATE kitchen_stocks SET quantity = ? WHERE item_name = ?");
            $stmt->execute([$newQuantity, $item_name]);

            // Insert into the sales table
            $stmt = $pdo->prepare("INSERT INTO sales (item_name, quantity, sale_date, location) VALUES (?, ?, ?, ?)");
            $stmt->execute([$item_name, $quantity, $sale_date, $location]);

            // Redirect to avoid re-submission
            header('Location: sales_page.php');
            exit;

        } else {
            // If not enough stock, show an error message
            $error = "Not enough stock in kitchen for this item.";
        }
    }
}

// Fetch sales records from database
$stmt = $pdo->prepare("SELECT * FROM sales ORDER BY sale_date DESC");
$stmt->execute();
$sales = $stmt->fetchAll();

// Calculate total sales for store and kitchen (by quantity sold)
$totalStoreSales = 0;
$totalKitchenSales = 0;

foreach ($sales as $sale) {
    if ($sale['location'] === 'store') {
        $totalStoreSales += $sale['quantity'];
    } else {
        $totalKitchenSales += $sale['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Tracking Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { background-color: #f4f6f9; }
        .card {
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            text-align: center;
        }
        /* Sidebar Styles */
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
    <div class="container mt-5">
        <h2 class="text-center">Sales Tracking</h2>

        <!-- Sales Total Overview -->
        <div class="row">
            <div class="col-md-6">
                <div class="card bg-light">
                    <h5 class="card-title">Total Sales from Store</h5>
                    <p class="card-text"><?php echo $totalStoreSales; ?> items</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light">
                    <h5 class="card-title">Total Sales from Kitchen</h5>
                    <p class="card-text"><?php echo $totalKitchenSales; ?> items</p>
                </div>
            </div>
        </div>

        <!-- Error Message (if any) -->
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- Sales Table -->
        <h4 class="mt-4">Sales Records</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Sale Date</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sale['item_name']); ?></td>
                        <td><?php echo $sale['quantity']; ?></td>
                        <td><?php echo $sale['sale_date']; ?></td>
                        <td><?php echo ucfirst($sale['location']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Add Sales Form -->
        <h4 class="mt-4">Add New Sales Record</h4>
        <form method="POST">
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <select id="location" name="location" class="form-control" required>
                    <option value="">Select Location</option>
                    <option value="store">Store</option>
                    <option value="kitchen">Kitchen</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <select id="item_name" name="item_name" class="form-control" required>
                    <option value="">Select Item</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" id="quantity" name="quantity" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="sale_date" class="form-label">Sale Date</label>
                <input type="date" id="sale_date" name="sale_date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Sales Record</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        // On location change, load items based on the selected location
        $('#location').change(function() {
            var location = $(this).val();
            var itemSelect = $('#item_name');
            itemSelect.empty(); // Clear the items dropdown

            if (location) {
                // AJAX call to fetch items from the selected location
                $.ajax({
                    url: 'fetch_items2.php',
                    method: 'GET',
                    data: { location: location },
                    success: function(response) {
                        var items = JSON.parse(response);
                        itemSelect.append('<option value="">Select Item</option>'); // Default option
                        items.forEach(function(item) {
                            itemSelect.append('<option value="' + item.item_name + '">' + item.item_name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>

</body>
</html>
