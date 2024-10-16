<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["jobTitle"])) {
    $jobID = $_POST["jobTitle"];
    require_once "inc/dbconn.inc.php";

    // Get current values
    $currentQuery = "SELECT jobStatus, location, jobComments FROM Jobs WHERE jobID=?";
    $stmt = mysqli_prepare($conn, $currentQuery);
    mysqli_stmt_bind_param($stmt, 'i', $jobID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $currentValues = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    // Get new values or use current if empty
    $newJobStatus = !empty($_POST["newJobStatus"]) ? $_POST["newJobStatus"] : $currentValues['jobStatus'];
    $newLocation = !empty($_POST["newLocation"]) ? $_POST["newLocation"] : $currentValues['location'];
    $jobComments = !empty($_POST["jobComments"]) ? $_POST["jobComments"] : $currentValues['jobComments'];

    // Update job details
    $sql = "UPDATE Jobs SET jobStatus=?, location=?, jobComments=? WHERE jobID=?;";
    $statement = mysqli_stmt_init($conn);


    if (mysqli_stmt_prepare($statement, $sql)) {
        mysqli_stmt_bind_param($statement, 'sssi', $newJobStatus, $newLocation, $jobComments, $jobID);

        if (mysqli_stmt_execute($statement)) {
            header("Location: updateJobs.php");
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
    <title>Jobs</title>
    <meta charset="UTF-8">
    <meta name="author" content="Belinda Hok"/>
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/jobsscript.js" defer></script>
</head>

<body>
    <?php require_once "inc/menu.inc.php";?>

    <main>

        <div class="container">
            <h1>Update Jobs</h1>
            
            <table id="jobsTable">
                <thead>
                    <tr>
                        <th>Job</th>
                        <th>Status</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require_once "inc/dbconn.inc.php";

                    $sql = "SELECT jobID, jobTitle, jobStatus, location, date, jobComments FROM Jobs;";

                    if ($result = mysqli_query($conn, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["jobTitle"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["jobStatus"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["location"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["date"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["jobComments"] ?? '') . "</td>";
                                echo "<td>
                                <button class='open-modal' data-id='" . htmlspecialchars($row["jobID"]) . "' data-title='" . htmlspecialchars($row["jobTitle"]) . "'>Update</button>
                                </td>";
                                echo "</tr>";
                            }

                            mysqli_free_result($result);
                        } 

                        else {
                            echo "<tr><td colspan='6'>No jobs found.</td></tr>";
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
                <h2>Update Job</h2>
                <form id="updateForm" method='post'>
                    <input type='hidden' name='jobTitle' id='jobTitle'>

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

</body>
</html>
