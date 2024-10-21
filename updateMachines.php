<?php
require_once "Database/db_connect.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["machineID"])) {
    $machineID = $_POST["machineID"];
    
    // Get current values
    $currentQuery = "SELECT status, machineComments FROM machine_data WHERE machine_id=:machine_id";
    $stmtCurrent = $pdo->prepare($currentQuery);
    $stmtCurrent->bindParam(':machine_id', $machineID, PDO::PARAM_INT);
    $stmtCurrent->execute();
    $currentValues = $stmtCurrent->fetch(PDO::FETCH_ASSOC);

    // Get new values or use current if empty
    $newMachineStatus = !empty($_POST["newMachineStatus"]) ? $_POST["newMachineStatus"] : $currentValues['status'];
    $machineComments = !empty($_POST["machineComments"]) ? $_POST["machineComments"] : $currentValues['machineComments'];

    // Update machine details
    $sql = "UPDATE machine_data SET status=:status, machineComments=:machineComments WHERE machine_id=:machine_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $newMachineStatus, PDO::PARAM_STR);
    $stmt->bindParam(':machineComments', $machineComments, PDO::PARAM_STR);
    $stmt->bindParam(':machine_id', $machineID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: updateMachines.php");
        exit();
    } else {
        echo "Error updating record.";
    }
}

// Fetch machine data
$sql = "SELECT machine_id, machine_name, status, createdTime, updatedTime, machineComments FROM machine_data";
$stmtSQL = $pdo->prepare($sql);
$stmtSQL->execute();
$machines = $stmtSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Belinda Hok"/>
    <title>Production Operator Machines</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>

<body>
    <header>
        <h1>Machines</h1>
        <div class="notification">
            <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
            <span class="notification-count">2</span>
        </div>
        <div class="user-info">
            <span>Brian Wessex</span>
            <span class="user-role">Production Operator</span>
        </div>
    </header>

    <nav>
        <ul class="nav-menu">
            <li><a href="homeProductionOp.php" class="active"><img src="images/home.png" alt="Home"> </a></li>
            <li><a href="#"><img src="images/performance.png" alt="Factory Performance"> </a></li>
            <li><a href="updateMachines.php"><img src="images/machine.png" alt="Machine Management"> </a></li>
            <li><a href="updateJobs.php"><img src="images/employees.png" alt="Job Management"> </a></li>
            <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> </a></li>
            <li><a href="events.html"><img src="images/calendar.png" alt="Events"> </a></li>
            <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> </a></li>
        </ul>
    </nav>

    <main>
        <div class="container">
            <h2>Update Machines</h2>
            
            <table id="machinesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Machine</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Updated Date</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php
                    if ($machines) {
                        foreach ($machines as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["machine_id"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["machine_name"] ?? '') . "</td>";
                            $statusClass = "";
                            switch ($row["status"]) {
                                case 'Active':
                                    $statusClass = "machine-status-active";
                                    break;
                                case 'Idle':
                                    $statusClass = "machine-status-idle";
                                    break;
                                case 'Maintenance':
                                    $statusClass = "machine-status-maintenance";
                                    break;
                            }
                            echo "<td class='$statusClass'>" . htmlspecialchars($row["status"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["createdTime"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["updatedTime"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["machineComments"] ?? '') . "</td>";
                            echo "<td>
                            <button class='open-modal' data-id='" . htmlspecialchars($row["machine_id"]) . "' data-title='" . htmlspecialchars($row["machine_name"]) . "'>Update</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No machines found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Update Machine</h2>
                <form id="updateForm" method='post'>
                    <input type='hidden' name='machineID' id='machineID'>

                    <label for="updateMachineStatus">Status:</label>
                    <select name='newMachineStatus' id='newMachineStatus'>
                        <option value=''>Select Status</option>
                        <option value='Active'>Active</option>
                        <option value='Idle'>Idle</option>
                        <option value='Maintenance'>Maintenance</option>
                    </select>

                    <label for="updateMachineComments"> Comments:</label>
                    <textarea name='machineComments' placeholder='Comments' id='machineComments' rows='4'></textarea>

                    <button type='submit' class='update-button'>Update</button>
                </form>
            </div>
        </div>
    </main>

<script>
    let modal = document.getElementById("myModal");
    let span = document.getElementsByClassName("close")[0];
    let buttons = document.querySelectorAll(".open-modal");

    buttons.forEach(function(button) {
        button.onclick = function() {
            let machineID = this.getAttribute("data-id");
            let machineTitle = this.getAttribute("data-title");

            document.getElementById("machineID").value = machineID;

            modal.style.display = "block";
        };
    });

    span.onclick = function() {
        modal.style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
</script>

</body>
</html>
