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
        <!-- Machine Performance Section -->
        <section id="factory-performance">
            <h2>Factory Performance</h2>
            <div style="height: 160px; overflow-y:auto;">
                <table id="performance-table">
                    <thead>
                        <tr>
                            <th>Machine ID</th>
                            <th>Machine Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($machineData as $row): ?>
                            <tr>
                                <td><?php echo $row['machine_id']; ?></td>
                                <td><?php echo $row['machine_name']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- Machine Management Section -->
        <section id="machine-management">
            <h2>Manage Machines</h2>
            <div style="height: 450px; overflow-y: auto;">
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
        </section>
    </div>
<script src="script.js"></script>
</body>
</html>
