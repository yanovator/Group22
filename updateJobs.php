<?php
require_once "Database/db_connect.php"; // Adjust the path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["jobID"])) {
    $jobID = $_POST["jobID"];

    // Get current values
    $currentQuery = "SELECT jobStatus, location, jobComments FROM Jobs WHERE jobID=:jobID";
    $stmt = $pdo->prepare($currentQuery);
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);
    $stmt->execute();
    $currentValues = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get new values or use current if empty
    $newJobStatus = !empty($_POST["newJobStatus"]) ? $_POST["newJobStatus"] : $currentValues['jobStatus'];
    $newLocation = !empty($_POST["newLocation"]) ? $_POST["newLocation"] : $currentValues['location'];
    $jobComments = !empty($_POST["jobComments"]) ? $_POST["jobComments"] : $currentValues['jobComments'];

    // Update job details
    $sql = "UPDATE Jobs SET jobStatus=:jobStatus, location=:location, jobComments=:jobComments WHERE jobID=:jobID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':jobStatus', $newJobStatus, PDO::PARAM_STR);
    $stmt->bindParam(':location', $newLocation, PDO::PARAM_STR);
    $stmt->bindParam(':jobComments', $jobComments, PDO::PARAM_STR);
    $stmt->bindParam(':jobID', $jobID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: updateJobs.php");
        exit();
    } else {
        echo "Error updating record.";
    }
}

// Fetch job data
$sql = "SELECT jobID, jobTitle, jobStatus, location, createdTime, updatedTime, jobComments FROM Jobs";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Belinda Hok"/>
    <title>Production Operator Jobs</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <header>
        <h1>Jobs</h1>
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
            <h2>Update Jobs</h2>
            
            <table id="jobsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Job</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Created Date</th>
                        <th>Updated Date</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    if ($jobs) {
                        foreach ($jobs as $row) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row["jobID"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["jobTitle"] ?? '') . "</td>";
                            $statusClass = "";
                            switch ($row["jobStatus"]) {
                                case 'In Progress':
                                    $statusClass = "job-status-in-progress";
                                    break;
                                case 'Completed':
                                    $statusClass = "job-status-completed";
                                    break;
                                case 'Waiting Parts':
                                    $statusClass = "job-status-waiting-parts";
                                    break;
                            }
                            echo "<td class='$statusClass'>" . htmlspecialchars($row["jobStatus"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["location"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["createdTime"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["updatedTime"] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row["jobComments"] ?? '') . "</td>";
                            echo "<td>
                            <button class='open-modal' data-id='" . htmlspecialchars($row["jobID"]) . "' data-title='" . htmlspecialchars($row["jobTitle"]) . "'>Update</button>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No jobs found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <input type="button" value="Create Task Note" class="taskNote" id="btn-note">
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Update Job</h2>
                <form id="updateForm" method='post'>
                    <input type='hidden' name='jobID' id='jobID'>

                    <label for="updateJobStatus">Status:</label>
                    <select name='newJobStatus' id='newJobStatus'>
                        <option value=''>Select Status</option>
                        <option value='In Progress'>In Progress</option>
                        <option value='Completed'>Completed</option>
                        <option value='Waiting Parts'>Waiting Parts</option>
                    </select>

                    <label for="updateLocation">Location:</label>
                    <input type='text' name='newLocation' placeholder='New Location' id='newLocation'>

                    <label for="updateJobComments"> Comments:</label>
                    <textarea name='jobComments' placeholder='Comments' id='jobComments' rows='4'></textarea>

                    <button type='submit' class='update-button'>Update</button>
                </form>
            </div>
        </div>
    </main>

<script>
    let modal = document.getElementById("myModal");
    let span = document.getElementsByClassName("close")[0];
    let buttons = document.querySelectorAll(".open-modal");

    document.getElementById("btn-note").onclick = function () {
        location.href = "create_note.php";
    };

    buttons.forEach(function(button) {
        button.onclick = function() {
            let jobID = this.getAttribute("data-id");
            let jobTitle = this.getAttribute("data-title");

            document.getElementById("jobID").value = jobID;

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
