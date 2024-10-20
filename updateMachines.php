<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["machine_name"])) {
    $machineID = $_POST["machine_name"];
    require_once "inc/dbconn.inc.php";  ////////////// chnage database? ////////////////////

    // Get current values
    $currentQuery = "SELECT status, machineComments FROM machine_data WHERE machine_id=?";
    $stmt = mysqli_prepare($conn, $currentQuery);
    mysqli_stmt_bind_param($stmt, 'i', $machineID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $currentValues = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    // Get new values or use current if empty
    $newMachineStatus = !empty($_POST["newMachineStatus"]) ? $_POST["newMachineStatus"] : $currentValues['status'];
    $machineComments = !empty($_POST["machineComments"]) ? $_POST["machineComments"] : $currentValues['machineComments'];

    // Update machine details
    $sql = "UPDATE machine_data SET status=?, machineComments=? WHERE machine_id=?;";
    $statement = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 'ssi', $newMachineStatus, $machineComments, $machineID);

        if (mysqli_stmt_execute($statement)) {
            header("Location: updateMachines.php");
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        echo "SQL statement preparation failed.";
    }

    mysqli_stmt_close($statement);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Belinda Hok"/>
    <title>Production Operator Machines</title>
    <link rel="stylesheet" href="styles/style.css">
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
                    require_once "inc/dbconn.inc.php";

                    $sql = "SELECT machine_id, machine_name, status, createdTime, updatedTime, machineComments FROM machine_data;";

                    if ($result = mysqli_query($conn, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
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

                            mysqli_free_result($result);
                        } 

                        else {
                            echo "<tr><td colspan='6'>No machines found.</td></tr>";
                        } 
                    } else {
                        echo "Error executing query: " . mysqli_error($conn);
                    }
                    
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Update Machine</h2>
                <form id="updateForm" method='post'>
                    <input type='hidden' name='machineTitle' id='machineTitle'>

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

            document.getElementById("machine_name").value = machineID;

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
