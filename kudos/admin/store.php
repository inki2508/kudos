<?php
session_start();
include 'db_connection.php'; // Connect to the database

// Fetch all items in Store (to display them)
$stmt = $pdo->prepare("SELECT * FROM store_stocks");
$stmt->execute();
$storeStocks = $stmt->fetchAll();

// Handle the form submission for adding stock to Store
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the selected item from the form and the quantity for Store
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $source_table = $_POST['source_table']; // To determine which table (Bodega 2, Office, or Counter)

    // Fetch the item details from the selected source table
    if ($source_table == 'bodega2') {
        $stmt = $pdo->prepare("SELECT item_name, quantity FROM bodega2_stocks WHERE id = :id");
    } elseif ($source_table == 'office') {
        $stmt = $pdo->prepare("SELECT item_name, quantity FROM office_stocks WHERE id = :id");
    } else {
        $stmt = $pdo->prepare("SELECT item_name, quantity FROM counter_stocks WHERE id = :id");
    }
    
    $stmt->bindParam(':id', $item_id);
    $stmt->execute();
    $item = $stmt->fetch();

    // Ensure there is enough stock in the selected source table
    if ($item['quantity'] < $quantity) {
        echo "Not enough stock in " . ucfirst($source_table) . ".";
        exit;
    }

    // Check if the item already exists in Store
    $stmt = $pdo->prepare("SELECT * FROM store_stocks WHERE item_name = :item_name");
    $stmt->bindParam(':item_name', $item['item_name']);
    $stmt->execute();
    $existingItem = $stmt->fetch();

    try {
        if ($existingItem) {
            // If item exists in Store, update the quantity
            $newQuantity = $existingItem['quantity'] + $quantity;
            $stmt = $pdo->prepare("UPDATE store_stocks SET quantity = :quantity WHERE item_name = :item_name");
            $stmt->bindParam(':quantity', $newQuantity);
            $stmt->bindParam(':item_name', $item['item_name']);
            $stmt->execute();
        } else {
            // If item does not exist in Store, insert it
            $stmt = $pdo->prepare("INSERT INTO store_stocks (item_name, quantity) VALUES (:item_name, :quantity)");
            $stmt->bindParam(':item_name', $item['item_name']);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        }

        // Decrease the quantity from the selected source table
        $newQuantity = $item['quantity'] - $quantity;
        if ($source_table == 'bodega2') {
            $stmt = $pdo->prepare("UPDATE bodega2_stocks SET quantity = :quantity WHERE id = :id");
        } elseif ($source_table == 'office') {
            $stmt = $pdo->prepare("UPDATE office_stocks SET quantity = :quantity WHERE id = :id");
        } else {
            $stmt = $pdo->prepare("UPDATE counter_stocks SET quantity = :quantity WHERE id = :id");
        }
        
        $stmt->bindParam(':quantity', $newQuantity);
        $stmt->bindParam(':id', $item_id);
        $stmt->execute();

        // Redirect to the Store Stocks page after adding stock
        header("Location: store.php");
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
    <title>Store Stocks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    <h2 class="mb-4">Store Stocks</h2>

    <!-- Table to Display Store Stocks -->
    <div class="dashboard-card">
        <h5 class="text-center">Stocks Available in Store</h5>
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
                <?php foreach ($storeStocks as $stock) { ?>
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

    <!-- Form to Add Stock to Store -->
    <div class="dashboard-card">
        <h5>Add Stock from Bodega 2, Office, or Counter to Store</h5>
        <form action="store.php" method="POST">
            <div class="mb-3">
                <label for="source_table" class="form-label">Select Source Table</label>
                <select class="form-select" id="source_table" name="source_table" required>
                    <option value="">Select Source</option>
                    <option value="bodega2">Bodega 2</option>
                    <option value="office">Office Stocks</option>
                    <option value="counter">Counter Stocks</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="item_id" class="form-label">Select Item</label>
                <select class="form-select" id="item_id" name="item_id" required>
                    <option value="">Select an item</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Stock to Store</button>
        </form>
    </div>
</div>

<script>
    // JavaScript to fetch items based on the selected source
    $(document).ready(function() {
        $('#source_table').change(function() {
            var source = $(this).val();
            var itemSelect = $('#item_id');
            itemSelect.empty(); // Clear previous options
            itemSelect.append('<option value="">Select Item</option>'); // Default option

            if (source) {
                $.ajax({
                    url: 'fetch_items.php', // A PHP script to fetch items based on the source
                    method: 'GET',
                    data: { source: source },
                    success: function(response) {
                        var items = JSON.parse(response);
                        items.forEach(function(item) {
                            itemSelect.append('<option value="' + item.id + '">' + item.item_name + '</option>');
                        });
                    }
                });
            }
        });
    });
</script>

</body>
</html>
