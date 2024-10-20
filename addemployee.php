<?php
// AddEmployee.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $id = $_POST['id'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate that passwords match
    if ($password !== $confirm_password) {
        echo "";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
        $sql = "INSERT INTO employees (name, id, role, email, password) VALUES ('$name', '$id', '$role', '$email', '$hashed_password')";

        // Execute the query and handle the result
        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }

        // Close connection
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><a href="#" class="active"><img src="images/performance.png" alt="Fractory Performance"></a></li>
            <li><a href="employees.php"><img src="images/employees.png" alt="Employees"></a></li>
            <li><a href="#" class="active"><img src="images/inbox.png" alt="Inbox"></a></li>
            <li><a href="#" class="active"><img src="images/calendar.jpg" alt="Events"></a></li>
            <li><a href="#" class="active"><img src="images/settings.png" alt="settings"></a></li>
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
                <input type="number" id="id" name="id" placeholder="ID" required>
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

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <button type="submit">Add Employee</button>
        </form>
        <a href="employees.php" class="back-link">Back to Employee List</a>
    </div>
    </main>
</body>
</html>