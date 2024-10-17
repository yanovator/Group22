<?php
//editemployee.php

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

// Get the employee ID from the URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Fetch employee details based on the ID
    $sql = "SELECT name, id, role, email FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();
        } else {
            die("<p>Error: Employee not found.</p>");
        }
        $stmt->close();
    } else {
        die("<p>Error preparing statement: " . $conn->error . "</p>");
    }
} else {
    die("<p>Error: No employee ID specified.</p>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';

    // Update employee details in the database
    $update_sql = "UPDATE employees SET name = ?, role = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    if ($stmt) {
        $stmt->bind_param("sssi", $name, $role, $email, $id);
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

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
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
        <div class="form-container">
            <h2>Edit Employee</h2>
            <form action="editemployee.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="">Select Role</option>
                        <option value="Administrator" <?php echo ($employee['role'] == 'Administrator') ? 'selected' : ''; ?>>Administrator</option>
                        <option value="Factory Manager" <?php echo ($employee['role'] == 'Factory Manager') ? 'selected' : ''; ?>>Factory Manager</option>
                        <option value="Production Operator" <?php echo ($employee['role'] == 'Production Operator') ? 'selected' : ''; ?>>Production Operator</option>
                        <option value="Auditor" <?php echo ($employee['role'] == 'Auditor') ? 'selected' : ''; ?>>Auditor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                </div>

                <button type="submit">Update Employee</button>
            </form>
            <a href="employees.php" class="back-link">Back to Employee List</a>
        </div>
    </main>
</body>
</html>