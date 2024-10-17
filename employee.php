<?php
// employees.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete employee if a POST request with delete action is made
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id']; 

    // Prepare and execute the delete query
    $delete_sql = "DELETE FROM employees WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    if ($stmt) {
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute() === TRUE) {
            header("Location: employees.php");
            exit();
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
    }
}

// Fetch employee data including email
$sql = "SELECT id, name, role, email FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Callum Forbes" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="./Styles/styles.css">
</head>
<body>
    <header>
        <h1>List of Employees</h1>
        <div class="notification">
            <img src="images/bell.jpg" alt="Notifications" class="bell-icon">
            <span class="notification-count">2</span>
        </div>
        <div class="user-info">
            <span>(name)</span>
            <span class="user-role">(Role)</span>
        </div>
    </header>

    <nav>
        <ul class="nav-menu">
            <li><a href="Administrator.html" class="active"><img src="images/home.png" alt="Home"></a></li>
            <li><a href="#" class="active"><img src="images/performance.png" alt="Factory Performance"></a></li>
            <li><a href="employees.php"><img src="images/employees.png" alt="Employees"></a></li>
            <li><a href="#" class="active"><img src="images/inbox.png" alt="Inbox"></a></li>
            <li><a href="#" class="active"><img src="images/calendar.jpg" alt="Events"></a></li>
            <li><a href="#" class="active"><img src="images/settings.png" alt="Settings"></a></li>
        </ul>
    </nav>

    <main>
        <h2>Employee Management</h2>

        <div class="search-add">
            <input type="text" placeholder="Search" class="search-bar">
            <a href="addemployee.php">
                <button class="add-button">Add New Employee</button>
            </a>
        </div>

        <table class="employee-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Actions</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td><a href='editemployee.php?id=" . htmlspecialchars($row["id"]) . "' class='edit-button'>Edit</a></td>";
                        echo "<td>
                                <form action='employees.php' method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='delete_id' value='" . htmlspecialchars($row["id"]) . "'>
                                    <button type='submit' class='delete-button'>
                                        <img src='images/delete.png' alt='Delete' class='trash-icon'>
                                    </button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No employees found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>