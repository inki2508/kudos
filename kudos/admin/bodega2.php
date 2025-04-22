<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Fetch all items from Bodega 1 (to add stocks to Bodega 2)
$stmt = $pdo->prepare("SELECT id, item_name, quantity FROM bodega1_stocks");
$stmt->execute();
$bodega1Items = $stmt->fetchAll();

// Fetch all items from Bodega 2 (to display them)
$stmt = $pdo->prepare("SELECT * FROM bodega2_stocks");
$stmt->execute();
$bodega2Stocks = $stmt->fetchAll();

// Handle the form submission for adding stock to Bodega 2
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected item from Bodega 1 and the quantity for Bodega 2
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Fetch the item details from Bodega 1
    $stmt = $pdo->prepare("SELECT item_name, quantity FROM bodega1_stocks WHERE id = :id");
    $stmt->bindParam(':id', $item_id);
    $stmt->execute();
    $item = $stmt->fetch();

    // Ensure there is enough stock in Bodega 1
    if ($item['quantity'] < $quantity) {
        echo "Not enough stock in Bodega 1.";
        exit;
    }

    // Check if the item already exists in Bodega 2
    $stmt = $pdo->prepare("SELECT * FROM bodega2_stocks WHERE item_name = :item_name");
    $stmt->bindParam(':item_name', $item['item_name']);
    $stmt->execute();
    $existingItem = $stmt->fetch();

    try {
        if ($existingItem) {
            // If item exists in Bodega 2, update the quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE bodega2_stocks SET quantity = :quantity WHERE item_name = :item_name");
            $stmt->bindParam(':quantity', $newQuantity);
            $stmt->bindParam(':item_name', $item['item_name']);
            $stmt->execute();
        } else {
            // If item does not exist in Bodega 2, insert it
            $stmt = $pdo->prepare("INSERT INTO bodega2_stocks (item_name, quantity) VALUES (:item_name, :quantity)");
            $stmt->bindParam(':item_name', $item['item_name']);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        }

        // Decrease the quantity from Bodega 1
        $newBodega1Quantity = $item['quantity'] - $quantity;
        $stmt = $pdo->prepare("UPDATE bodega1_stocks SET quantity = :quantity WHERE id = :id");
        $stmt->bindParam(':quantity', $newBodega1Quantity);
        $stmt->bindParam(':id', $item_id);
        $stmt->execute();

        // Redirect to the Bodega 2 page after adding stock
        header("Location: bodega2.php");
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
    <title>Bodega 2 Stocks</title>
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
    <h2 class="mb-4">Bodega 2 Stocks</h2>

    <!-- Table to Display Bodega 2 Stocks -->
    <div class="dashboard-card">
        <h5 class="text-center">Stocks Available in Bodega 2</h5>
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
                <?php foreach ($bodega2Stocks as $stock) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($stock['id']); ?></td>
                        <td><?php echo htmlspecialchars($stock['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($stock['quantity']); ?></td>
                        <td>
                            <a href="edit_stock.php?id=<?php echo $stock['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_stock.php?id=<?php echo $stock['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Form to Add Stock to Bodega 2 -->
    <div class="dashboard-card">
        <h5>Add Stock from Bodega 1 to Bodega 2</h5>
        <form action="bodega2.php" method="POST">
            <div class="mb-3">
                <label for="item_id" class="form-label">Select Item from Bodega 1</label>
                <select class="form-select" id="item_id" name="item_id" required>
                    <option value="">Select an item</option>
                    <?php foreach ($bodega1Items as $item) { ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo htmlspecialchars($item['item_name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-success">Add Stock to Bodega 2</button>
        </form>
    </div>
</div>

</body>
</html>
