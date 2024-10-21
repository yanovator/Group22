<?php
// Connect to the database
require_once "inc/dbconn.inc.php"; // Update this path if necessary

// Initialize variables to count machine statuses and maintenance categories
$totalMachines = 0;
$partReplacement = 0;
$softwareUpdate = 0;
$catastrophicFailure = 0;
$routineCheck = 0;

$activeMachines = 0;
$idleMachines = 0;
$maintenanceMachines = 0;

if (($file = fopen('factory_logs.csv', 'r')) !== FALSE) {
    // Skip the header row
    fgetcsv($file);

    // Process the CSV data to calculate machine statuses
    while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
        $totalMachines++;

        // Fetch operational_status from the 8th column (index 7)
        $operationalStatus = strtolower($data[7]);

        // Count machine status based on operational_status
        if ($operationalStatus === 'active') {
            $activeMachines++;
        } elseif ($operationalStatus === 'idle') {
            $idleMachines++;
        } elseif ($operationalStatus === 'maintenance') {
            $maintenanceMachines++;
        }

        // Fetch maintenance_log from the 11th column (index 10)
        $maintenanceLog = strtolower($data[10]);

        // Count maintenance types based on maintenance_log
        if ($maintenanceLog === 'part replacement') {
            $partReplacement++;
        } elseif ($maintenanceLog === 'software update') {
            $softwareUpdate++;
        } elseif ($maintenanceLog === 'catastrophic failure') {
            $catastrophicFailure++;
        } elseif ($maintenanceLog === 'routine check') {
            $routineCheck++;
        }
    }

    fclose($file);
} else {
    // Handle file opening error
    echo "<p>Error: Unable to open the CSV file.</p>";
}

// Query the database to get job details (unchanged)
$jobsQuery = "SELECT jobTitle, jobStatus, jobComments FROM Jobs";
$jobsResult = mysqli_query($conn, $jobsQuery);

$totalJobsCompleted = 0;
$jobsInProgress = 0;
$jobsWaitingParts = 0;
$assignedJobs = [];

if ($jobsResult && mysqli_num_rows($jobsResult) > 0) {
    while ($row = mysqli_fetch_assoc($jobsResult)) {
        // Escape output to prevent XSS
        $jobTitle = htmlspecialchars($row['jobTitle'], ENT_QUOTES, 'UTF-8');
        $jobStatus = htmlspecialchars($row['jobStatus'], ENT_QUOTES, 'UTF-8');
        $jobComments = htmlspecialchars($row['jobComments'], ENT_QUOTES, 'UTF-8');

        // Determine job status counts
        if ($jobStatus == 'Completed') {
            $totalJobsCompleted++;
        } elseif ($jobStatus == 'In Progress') {
            $jobsInProgress++;
        } elseif ($jobStatus == 'Waiting Parts') {
            $jobsWaitingParts++;
        }

        // Add job details to the assignedJobs array
        $assignedJobs[] = [
            'job_name' => $jobTitle,
            'status' => $jobStatus,
            'comments' => $jobComments,
        ];
    }
} else {
    // Handle SQL query error
    echo "<p>Error: Unable to fetch jobs data from the database.</p>";
}

mysqli_close($conn);
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
    <div class="wrapper">
        <nav class="sidebar">
            <ul class="nav-menu">
                <li><a href="homeProductionOp.php"><img src="images/home.png" alt="Home"> Home</a></li>
                <li><a href="#" class="active"><img src="images/performance.png" alt="Factory Performance"> Performance</a></li>
                <li><a href="updateMachines.php"><img src="images/machine.png" alt="Machine Management"> Machines</a></li>
                <li><a href="updateJobs.php"><img src="images/employees.png" alt="Job Management"> Jobs</a></li>
                <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> Inbox</a></li>
                <li><a href="events.html"><img src="images/calendar.png" alt="Events"> Events</a></li>
                <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> Settings</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header>
                <h1>Factory Performance and Job Progress</h1>
                <div class="user-info">
                    <span>Brian Wessex</span>
                    <span class="user-role">Production Operator</span>
                    <div class="notification">
                        <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
                        <span class="notification-count">2</span>
                    </div>
                </div>
            </header>

            <!-- Factory Performance Heading -->
            <h2 class="section-heading">Factory Performance</h2>

            <!-- Machine Status Heading -->
            <h3 class="section-subheading">Machine Status</h3>

            <!-- Machine Data Tiles (displayed horizontally) -->
            <div class="machine-status-tiles-horizontal">
                <div class="machine-tile card">
                    <p>Total Machines</p>
                    <h3><?php echo $totalMachines; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Active Machines</p>
                    <h3><?php echo $activeMachines; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Idle Machines</p>
                    <h3><?php echo $idleMachines; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Machines Under Maintenance</p>
                    <h3><?php echo $maintenanceMachines; ?></h3>
                </div>
            </div>

            <!-- Maintenance Log Tiles Heading -->
            <h3 class="section-subheading">Maintenance Log</h3>

            <!-- Maintenance Log Data Tiles (displayed horizontally) -->
            <div class="maintenance-tiles-horizontal">
                <div class="machine-tile card">
                    <p>Part Replacement</p>
                    <h3><?php echo $partReplacement; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Software Update</p>
                    <h3><?php echo $softwareUpdate; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Catastrophic Failure</p>
                    <h3><?php echo $catastrophicFailure; ?></h3>
                </div>

                <div class="machine-tile card">
                    <p>Routine Check</p>
                    <h3><?php echo $routineCheck; ?></h3>
                </div>
            </div>

            <!-- Job Stats Heading -->
            <div class="job-stats-heading card">
                <h2>Job Stats</h2>
            </div>

            <!-- Job Stats Section with Individual Tiles -->
            <div class="job-stats">
                <div class="stat-box card">
                    <p>Jobs Completed</p>
                    <h3><?php echo $totalJobsCompleted; ?></h3>
                </div>
                <div class="stat-box card">
                    <p>Jobs In Progress</p>
                    <h3><?php echo $jobsInProgress; ?></h3>
                </div>
                <div class="stat-box card">
                    <p>Jobs Awaiting Parts</p>
                    <h3><?php echo $jobsWaitingParts; ?></h3>
                </div>
            </div>

            <!-- Job Summary Section -->
            <div class="job-summary-container">
                <h2>Job Summary</h2>
                <?php foreach ($assignedJobs as $job): ?>
                    <div class="job-summary-tile">
                        <h3><?php echo htmlspecialchars($job['job_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p>Status: <?php echo htmlspecialchars($job['status'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Comments: <?php echo htmlspecialchars($job['comments'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
