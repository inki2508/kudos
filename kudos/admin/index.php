<?php
session_start();
include 'db_connection.php'; // Connect to the database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Inventory System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .dashboard-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .dashboard-card h5 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        .dashboard-card .badge {
            font-size: 1.1rem;
            font-weight: bold;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }
        .dashboard-header h2 {
            margin-bottom: 10px;
        }
        .dashboard-header p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        .dashboard-card ul {
            list-style-type: none;
            padding-left: 0;
        }
        .dashboard-card .list-group-item {
            padding: 12px;
            margin-bottom: 10px;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-primary {
            background-color: #007bff;
        }
        .chart-container {
            position: relative;
            height: 200px;
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
    <div class="dashboard-header">
        <h2>Welcome, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Inventory Staff'; ?>!</h2>
        <p>Your dashboard provides a quick overview of your inventory status.</p>
    </div>

    <div class="row">
        <?php
        // Array of table names and their display labels
        $locations = [
            'store_stocks' => 'Store Stocks',
            'bodega1_stocks' => 'Bodega 1 Stocks',
            'bodega2_stocks' => 'Bodega 2 Stocks',
            'office_stocks' => 'Office Stocks',
            'counter_stocks' => 'Counter Stocks',
            'kitchen_stocks' => 'Kitchen Stocks'
        ];

        // Thresholds for item classification
        $lowThreshold = 10;
        $highThreshold = 100;

        // Loop through each location table and fetch categorized items
        foreach ($locations as $table => $label) {
            $stmt = $pdo->prepare("SELECT item_name, quantity FROM {$table}");
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            // Categorize items
            $lowItems = [];
            $neutralItems = [];
            $overstockItems = [];

            foreach ($items as $item) {
                if ($item['quantity'] < $lowThreshold) {
                    $lowItems[] = $item;
                } elseif ($item['quantity'] > $highThreshold) {
                    $overstockItems[] = $item;
                } else {
                    $neutralItems[] = $item;
                }
            }
        ?>
            <!-- Stock Location Card -->
            <?php if ($table == 'store_stocks') { ?>
                <div class="col-12">
                    <div class="dashboard-card" style="box-shadow: 0 8px 15px rgba(0,0,0,0.1);">
                        <h5 class="text-center"><?php echo $label; ?> (Main Dashboard)</h5>
                        <h6 class="mt-4">Running Low Items (<?php echo count($lowItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($lowItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-danger"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                        <h6 class="mt-4">Overstock Items (<?php echo count($overstockItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($overstockItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-success"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                        <h6 class="mt-4">Neutral Items (<?php echo count($neutralItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($neutralItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-primary"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            <?php } else { ?>
                <!-- Other Location Cards -->
                <div class="col-md-4">
                    <div class="dashboard-card">
                        <h5 class="text-center"><?php echo $label; ?></h5>
                        
                        <h6 class="mt-4">Running Low Items (<?php echo count($lowItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($lowItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-danger"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                        <h6 class="mt-4">Overstock Items (<?php echo count($overstockItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($overstockItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-success"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                        <h6 class="mt-4">Neutral Items (<?php echo count($neutralItems); ?>)</h6>
                        <ul class="list-group">
                            <?php foreach ($neutralItems as $item) { ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <?php echo htmlspecialchars($item['item_name']); ?>
                                    <span class="badge badge-primary"><?php echo $item['quantity']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>

                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <!-- Sales Mini Graphs -->
        <?php
        // Fetch sales data for Store and Kitchen
        $locations = ['Store', 'Kitchen'];

        foreach ($locations as $location) {
            // Fetch top 10 items by quantity sold for each location
            $stmt = $pdo->prepare("SELECT item_name, SUM(quantity) AS total_sold 
                                   FROM sales 
                                   WHERE location = :location 
                                   GROUP BY item_name 
                                   ORDER BY total_sold DESC 
                                   LIMIT 10");
            $stmt->execute(['location' => $location]);
            $salesData = $stmt->fetchAll();
            
            $itemNames = [];
            $quantities = [];

            foreach ($salesData as $data) {
                $itemNames[] = $data['item_name'];
                $quantities[] = $data['total_sold'];
            }
        ?>
            <!-- Sales Chart Card -->
            <div class="col-md-6">
                <div class="dashboard-card">
                    <h5 class="text-center"><?php echo $location; ?> - Top 10 Sales</h5>
                    <div class="chart-container">
                        <canvas id="salesChart_<?php echo $location; ?>"></canvas>
                    </div>
                    <script>
                        var ctx = document.getElementById('salesChart_<?php echo $location; ?>').getContext('2d');
                        var salesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode($itemNames); ?>,
                                datasets: [{
                                    label: 'Quantity Sold',
                                    data: <?php echo json_encode($quantities); ?>,
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
