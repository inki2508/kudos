<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Check if the ID is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "No stock ID specified.";
    exit;
}

$stock_id = $_GET['id'];

// Fetch the stock details from Bodega 1
$stmt = $pdo->prepare("SELECT * FROM bodega1_stocks WHERE id = :id");
$stmt->bindParam(':id', $stock_id);
$stmt->execute();
$stock = $stmt->fetch();

if (!$stock) {
    echo "Stock not found.";
    exit;
}

// Handle the form submission for editing stock
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    // Update the stock in Bodega 1
    try {
        $stmt = $pdo->prepare("UPDATE bodega1_stocks SET item_name = :item_name, quantity = :quantity WHERE id = :id");
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $stock_id);
        $stmt->execute();

        // Redirect back to the Bodega 1 page after successful update
        header("Location: bodega1.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Stock - Bodega 1</title>
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
    <h2 class="mb-4">Edit Stock - Bodega 1</h2>

    <!-- Form to Edit Stock in Bodega 1 -->
    <div class="dashboard-card">
        <h5>Edit Stock</h5>
        <form action="bodega1edit.php?id=<?php echo $stock['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" value="<?php echo htmlspecialchars($stock['item_name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($stock['quantity']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Stock</button>
        </form>
    </div>
</div>

</body>
</html>
