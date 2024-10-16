<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1); 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["machineTitle"])) {
    $machineID = $_POST["machineTitle"];
    require_once "inc/dbconn.inc.php";

    // Get current values
    $currentQuery = "SELECT machineStatus, machineComments FROM Machines WHERE machineID=?";
    $stmt = mysqli_prepare($conn, $currentQuery);
    mysqli_stmt_bind_param($stmt, 'i', $machineID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $currentValues = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    // Get new values or use current if empty
    $newMachineStatus = !empty($_POST["newMachineStatus"]) ? $_POST["newMachineStatus"] : $currentValues['machineStatus'];
    $machineComments = !empty($_POST["machineComments"]) ? $_POST["machineComments"] : $currentValues['machineComments'];

    // Update machine details
    $sql = "UPDATE Machines SET machineStatus=?, machineComments=? WHERE machineID=?;";
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
    <title>Machines</title>
    <meta charset="UTF-8">
    <meta name="author" content="Belinda Hok"/>
    <link rel="stylesheet" href="styles/style.css">
    <script src="scripts/machinescript.js" defer></script>
</head>

<body>
    <?php require_once "inc/menu.inc.php";?>

    <main>

        <div class="container">
            <h1>Update Machines</h1>
            
            <table id="machinesTable">
                <thead>
                    <tr>
                        <th>Machine</th>
                        <th>Status</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    require_once "inc/dbconn.inc.php";

                    $sql = "SELECT machineID, machineTitle, machineStatus, machineComments FROM Machines;";

                    if ($result = mysqli_query($conn, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["machineTitle"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["machineStatus"] ?? '') . "</td>";
                                echo "<td>" . htmlspecialchars($row["machineComments"] ?? '') . "</td>";
                                echo "<td>
                                <button class='open-modal' data-id='" . htmlspecialchars($row["machineID"]) . "' data-title='" . htmlspecialchars($row["machineTitle"]) . "'>Update</button>
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

</body>
</html>
