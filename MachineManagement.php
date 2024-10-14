<?php
$csvFile = fopen("factory_logs.csv", "r");

$machines = [];
$machineID = 1;

// Skip the first row (header row)
$firstRow = true;

while (($row = fgetcsv($csvFile)) !== false) {
    if ($firstRow) {
        $firstRow = false;
        continue;
    }

    $machineName = $row[1];
    $status = $row[7];

    $machines[$machineName] = [
        'id' => $machineID,
        'name' => $machineName,
        'status' => $status
    ];

    $machineID++;
}

fclose($csvFile);
?>

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
<div class="container">
    <h1>Factory Manager Dashboard</h1>

    <!-- Machine Performance Section -->
    <section id="factory-performance">
        <h2>Overall Factory Performance</h2>
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
                    <?php foreach ($machines as $machine): ?>
                        <tr>
                            <td><?= $machine['id'] ?></td>
                            <td><?= htmlspecialchars($machine['name']) ?></td>
                            <td><?= htmlspecialchars($machine['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Machine Management Section -->
    <section id="machine-management">
        <h2>Manage Machines</h2>
        <div style="height: 450px; overflow-y:auto;">
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
                    <?php foreach ($machines as $machine): ?>
                        <tr>
                            <td><input type="text" value="<?= $machine['id'] ?>" readonly></td>
                            <td><input type="text" value="<?= htmlspecialchars($machine['name']) ?>" class="edit-machine-name"></td>
                            <td><input type="text" value="" placeholder="operator name"></td>
                            <td>
                                <select class="edit-machine-status">
                                    <option value="active" <?= $machine['status'] == 'active' ? 'selected' : '' ?>>active</option>
                                    <option value="idle" <?= $machine['status'] == 'idle' ? 'selected' : '' ?>>idle</option>
                                    <option value="Maintenance" <?= $machine['status'] == 'maintenance' ? 'selected' : '' ?>>maintenance</option>
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
