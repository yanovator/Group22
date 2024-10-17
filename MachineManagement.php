<?php
require_once 'Database/db_connect.php';

// Connection logic from db_connect.php
$pdo = new PDO('mysql:host=localhost;dbname=factory_logs', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch machine data
try {
    $sql = "SELECT * FROM machine_data"; 
    $stmt = $pdo->query($sql);
    $machineData = $stmt->fetchAll(PDO::FETCH_ASSOC); 
} catch (PDOException $e) {
    echo "Error fetching machine data: " . $e->getMessage();
}

// Fetch list of machines
try {
    $sqlMachines = "SELECT DISTINCT machine_name FROM machine_data";
    $stmtMachines = $pdo->query($sqlMachines);
    $machines = $stmtMachines->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching machines: " . $e->getMessage();
}

// Fetch list of machines under maintenance
try {
    $sqlMaintenance = "SELECT * FROM machine_data WHERE status = 'maintenance'";
    $stmtMaintenance = $pdo->query($sqlMaintenance);
    $maintenanceData = $stmtMaintenance->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching maintenance data: " . $e->getMessage();
}
?>

<!--
factory manager page to-do:
- deal with duplicate machines, PHP
- have maintenance history table
- have factory performance table
- put table(s) in carousel to easily switch views
- implement header w/ CSS
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Shay Yanchman">
    <title>Factory Management Dashboard</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
    <header>
        <h1>Factory Manager Dashboard</h1>
        <div class="notification">
            <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
            <span class="notification-count">2</span>
        </div>
        <div class="user-info">
            <span>Dohn Joe</span>
            <span class="user-role">Factory Manager</span>
        </div>
    </header>

    <nav>
        <ul class="nav-menu">
            <li><a href="Administrator.html" class="active"><img src="images/home.png" alt="Home"> </a></li>
            <li><a href="performance.html"><img src="images/performance.png" alt="Factory Performance"> </a></li>
            <li><a href="employees.php"><img src="images/employees.png" alt="Employees"> </a></li>
            <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> </a></li>
            <li><a href="events.html"><img src="images/calendar.jpg" alt="Events"> </a></li>
            <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> </a></li>
        </ul>
    </nav>

    <div class="FMcontainer">
        <div class="carousel">
            <button class="carousel-button prev" onclick="moveCarousel(-1)">&#10094;</button>
            <button class="carousel-button next" onclick="moveCarousel(1)">&#10095;</button>
            <div class="carousel-inner" id="carouselInner">
                <!-- Machine Management Section -->
                <div class="carousel-item">
                    <h2>Manage Machines</h2>
                    <div style="height: 650px; overflow-y: auto;">
                        <table id="machine-management-table">
                            <thead>
                                <tr>
                                    <th>Machine ID</th>
                                    <th>Machine Name</th>
                                    <th>Machine Operator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="machine-list">
                                <?php foreach ($machineData as $row): ?>
                                    <tr>
                                        <td><?php echo $row['machine_id']; ?></td>
                                        <td><?php echo $row['machine_name']; ?></td>
                                        <td><input type="text" value="" placeholder="operator name"></td>
                                        <td>
                                            <select class="edit-machine-status">
                                                <option value="active" <?= $row['status'] == 'active' ? 'selected' : '' ?>>active</option>
                                                <option value="idle" <?= $row['status'] == 'idle' ? 'selected' : '' ?>>idle</option>
                                                <option value="maintenance" <?= $row['status'] == 'maintenance' ? 'selected' : '' ?>>maintenance</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="update-machine-btn">Update</button>
                                            <button class="delete-machine-btn">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Form to Add New Machine -->
                    <form id="add-machine-form" method="post">
                        <input type="text" id="new-machine-id" placeholder="Machine ID" required>
                        <input type="text" id="new-machine-name" placeholder="Machine Name" required>
                        <input type="text" id="new-machine-op" placeholder="Operator" required>
                        <select id="new-machine-status">
                            <option value="active">active</option>
                            <option value="idle">idle</option>
                            <option value="maintenance">maintenance</option>
                        </select>
                        <button type="button" id="add-machine-btn">Add Machine</button>
                    </form>
                </div>
                <!-- Machine Performance Section -->
                <div class="carousel-item" style="overflow-y:auto;">
                    <h2>Factory Performance</h2>
                    <div style="height: 650px; overflow-y: auto;">
                        <table id="performance-table">
                            <thead>
                                <tr>
                                    <th>Machine ID</th>
                                    <th>Machine Name</th>
                                    <th>Temperature</th>
                                    <th>Pressure</th>
                                    <th>Vibration</th>
                                    <th>Humidity</th>
                                    <th>Power Consumption</th>
                                    <th>Production Count</th>
                                    <th>Speed</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($machineData as $row): ?>
                                    <tr>
                                        <td><?php echo $row['machine_id']; ?></td>
                                        <td><?php echo $row['machine_name']; ?></td>
                                        <td><?php echo $row['temperature']; ?></td>
                                        <td><?php echo $row['pressure']; ?></td>
                                        <td><?php echo $row['vibration']; ?></td>
                                        <td><?php echo $row['humidity']; ?></td>
                                        <td><?php echo $row['power_consumption']; ?></td>
                                        <td><?php echo $row['production_count']; ?></td>
                                        <td><?php echo $row['speed']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Maintenance Section -->
                <div class="carousel-item" style="overflow-y:auto;">
                    <h2>Machines Under Maintenance</h2>
                    <?php if (count($maintenanceData) > 0): ?>
                        <table id="maintenance-table">
                            <thead>
                                <tr>
                                    <th>Machine ID</th>
                                    <th>Machine Name</th>
                                    <th>Error Code</th>
                                    <th>Maintenance Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($maintenanceData as $row): ?>
                                    <tr>
                                        <td><?php echo $row['machine_id']; ?></td>
                                        <td><?php echo $row['machine_name']; ?></td>
                                        <td><?php echo $row['error_code']; ?></td>
                                        <td><?php echo $row['maintenance_log']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No machines are currently under maintenance.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<script src="script.js"></script>
<script>
    let currentIndex = 0;

    function moveCarousel(direction) {
        const carouselInner = document.getElementById('carouselInner');
        const items = document.querySelectorAll('.carousel-item');
        const totalItems = items.length;

        currentIndex += direction;

        // Loop through the carousel items
        if (currentIndex < 0) {
            currentIndex = totalItems - 1; // Go to the last item
        } else if (currentIndex >= totalItems) {
            currentIndex = 0; // Go to the first item
        }

        // Move the carousel to show the correct item
        carouselInner.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
    }
</script>

</body>
</html>
