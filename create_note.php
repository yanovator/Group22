<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task Notes</title>

    <!-- Link to custom CSS for styling -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Wrapper for the layout -->
    <div class="wrapper">
        <!-- Vertical Navigation Menu -->
        <nav class="sidebar">
            <ul class="nav-menu">
                <li><a href="homeProductionOp.php" class="active"><img src="images/home.png" alt="Home"> Home</a></li>
                <li><a href="#"><img src="images/performance.png" alt="Factory Performance"> Performance</a></li>
                <li><a href="updateMachines.php"><img src="images/machine.png" alt="Machine Management"> Machines</a></li>
                <li><a href="updateJobs.php"><img src="images/employees.png" alt="Job Management"> Jobs</a></li>
                <li><a href="inbox.html"><img src="images/inbox.png" alt="Inbox"> Inbox</a></li>
                <li><a href="events.html"><img src="images/calendar.png" alt="Events"> Events</a></li>
                <li><a href="settings.html"><img src="images/settings.png" alt="Settings"> Settings</a></li>
            </ul>
        </nav>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Header Section -->
            <header>
                <h1>Create Task Notes for Factory Manager</h1>
                <div class="user-info">
                    <div class="notification">
                        <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
                        <span class="notification-count">2</span>
                    </div>
                    <span>Brian Wessex</span>
                    <span class="user-role">Production Operator</span>
                </div>
            </header>

            <!-- Task note container with centered form -->
            <div class="task-note-container">
                <h2>Create Task Notes</h2>

                <!-- Form for submitting task notes -->
                <form action="submit_note.php" method="POST" id="taskNoteForm">
                    <label for="observation">Task Note: 
                        <small>(Describe your task note/observation)</small>
                    </label>
                    <textarea id="observation" name="observation" rows="4" required placeholder="Start typing..."></textarea>

                    <label for="manager">Assign to Factory Manager: 
                        <small>(Select the manager responsible by name or employee ID)</small>
                    </label>

                    <!-- Dropdown for factory managers (without search) -->
                    <select id="manager" name="manager" required>
                        <option value="">Select a Factory Manager</option>
                        <?php
                        // Connect to the database to fetch Factory Managers
                        $conn = new mysqli('localhost', 'root', '', 'factory_db');

                        // Check if the connection is successful
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // SQL query to get factory managers with employee ID and name
                        $sql = "SELECT id, employee_id, name FROM users WHERE role = 'manager'";
                        $result = $conn->query($sql);

                        // Loop through each manager and create a dropdown option with employee ID and name
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['employee_id'] . " - " . $row['name'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No managers available</option>";
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </select>

                    <!-- Submit button -->
                    <button type="submit" id="submitBtn">Submit Task Note</button>
                </form>
            </div> <!-- End of task-note-container -->
        </div> <!-- End of main content -->
    </div> <!-- End of wrapper -->
</body>
</html>

