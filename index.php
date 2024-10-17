<?php
// Open the CSV file
$file = fopen('factory_logs.csv', 'r');

// Variables to store important metrics
$totalMachines = 0;
$activeMachines = 0;
$inactiveMachines = 0;
$totalJobsCompleted = 5; // Example value
$totalJobsAwaiting = 2;  // Example value
$assignedJobs = [
    ['job_name' => 'Stocking Raw Materials', 'status' => 'In Progress', 'progress' => 80],
    ['job_name' => 'Lubrication of Machinery', 'status' => 'Waiting for Parts', 'progress' => 30]
];

// Skip the header row
fgetcsv($file);

// Process the CSV data to calculate machine statuses
while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
    $totalMachines++;

    if (strtolower($data[7]) === 'active') {
        $activeMachines++;
    } else {
        $inactiveMachines++;
    }
}

fclose($file);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factory Monitoring Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Brian Wessex - Factory Monitoring Dashboard</h1>
    </header>

    <!-- Job Stats Section -->
    <div class="job-stats">
        <h2>Job Stats</h2>
        <div class="stat-box">
            <p>Jobs Completed Today</p>
            <h3><?php echo $totalJobsCompleted; ?></h3>
        </div>
        <div class="stat-box">
            <p>Jobs Awaiting Completion</p>
            <h3><?php echo $totalJobsAwaiting; ?></h3>
        </div>
        <div class="stat-box">
            <p>Machines Active</p>
            <h3><?php echo $activeMachines; ?></h3>
        </div>
        <div class="stat-box">
            <p>Machines Inactive</p>
            <h3><?php echo $inactiveMachines; ?></h3>
        </div>
    </div>

    <!-- Summary of Jobs Assigned Section -->
    <div class="job-overview">
        <h2>Jobs Assigned</h2>
        <?php foreach ($assignedJobs as $job): ?>
        <div class="job-card">
            <h3><?php echo $job['job_name']; ?></h3>
            <p>Status: <?php echo $job['status']; ?></p>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo $job['progress']; ?>%;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Key Stats Section -->
    <div class="key-stats">
        <h2>Factory Summary</h2>
        <p>Total Machines: <strong><?php echo $totalMachines; ?></strong></p>
        <p>Machines Active: <strong><?php echo $activeMachines; ?></strong></p>
        <p>Machines Inactive: <strong><?php echo $inactiveMachines; ?></strong></p>
    </div>

    <script src="dashboard.js"></script>
</body>
</html>
