<?php
// AddEmployee.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $id = $_POST['id'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';

    // Server-side validation for ID length (must be 8 digits)
    if (!preg_match("/^\d{8}$/", $id)) {
        die("<p>Error: Employee ID must be exactly 8 digits.</p>");
    }

    // Server-side validation for role (must be one of the predefined values)
    $valid_roles = ['Administrator', 'Factory Manager', 'Production Operator', 'Auditor'];
    if (!in_array($role, $valid_roles)) {
        die("<p>Error: Invalid role selected.</p>");
    }

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

    // Insert employee details into the database
    $sql = "INSERT INTO employees (name, id, role, email) VALUES ('$name', '$id', '$role', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Callum Forbes" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
    <link rel="stylesheet" href="./Styles/styles.css">
</head>
<body>
<header>
        <h1>Add new employee</h1>
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
            <li><a href="employee.php"><img src="images/employees.png" alt="Employees"></a></li>
            <li><a href="#" class="active"><img src="images/inbox.png" alt="Inbox"></a></li>
            <li><a href="#" class="active"><img src="images/calendar.jpg" alt="Events"></a></li>
            <li><a href="#" class="active"><img src="images/settings.png" alt="Settings"></a></li>
        </ul>
    </nav>

    <main>
    <div class="form-container">
        <h2>Add New Employee</h2>
        <form action="AddEmployee.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Name" required>
            </div>

            <div class="form-group">
                <label for="id">ID</label>
                <input type="number" id="id" name="id" placeholder="8-digit ID" required
                       minlength="8" maxlength="8" min="10000000" max="99999999">
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Administrator">Administrator</option>
                    <option value="Factory Manager">Factory Manager</option>
                    <option value="Production Operator">Production Operator</option>
                    <option value="Auditor">Auditor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" required>
            </div>

            <button type="submit">Add Employee</button>
        </form>
        <a href="employee.php" class="back-link">Back to Employee List</a>
    </div>
</main>
</body>
</html>