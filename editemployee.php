<?php
// EditEmployee.php

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

// Fetch employee data if the ID is provided
$id = $_GET['id'] ?? '';
$employee = null;

if ($id) {
    $sql = "SELECT * FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $email = $_POST['email'] ?? '';
    $old_password = $_POST['old_password'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate old password if a new password is provided
    if (!empty($password)) {
        if (!password_verify($old_password, $employee['password'])) {
            echo "";
        } elseif ($password !== $confirm_password) {
            echo "";
        } else {
            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }
    } else {
        $hashed_password = $employee['password'];
    }

    // Update employee details in the database
    $sql = "UPDATE employees SET name = ?, role = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $role, $email, $hashed_password, $id);

    // Execute the query and handle the result
    if ($stmt->execute() === TRUE) {
        echo "";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

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
        <h1>Edit Employee</h1>
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
        <h2>Edit Employee</h2>
        <?php if ($employee): ?>
        <form action="EditEmployee.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="Administrator" <?php echo $employee['role'] == 'Administrator' ? 'selected' : ''; ?>>Administrator</option>
                    <option value="Factory Manager" <?php echo $employee['role'] == 'Factory Manager' ? 'selected' : ''; ?>>Factory Manager</option>
                    <option value="Production Operator" <?php echo $employee['role'] == 'Production Operator' ? 'selected' : ''; ?>>Production Operator</option>
                    <option value="Auditor" <?php echo $employee['role'] == 'Auditor' ? 'selected' : ''; ?>>Auditor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" value="<?php echo !empty($employee['email']) ? htmlspecialchars($employee['email']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="old_password">Old Password</label>
                <input type="password" id="old_password" name="old_password" placeholder="Old Password">
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="New Password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
            </div>

            <button type="submit">Update Employee</button>
        </form>
        <?php else: ?>
            <p>Employee not found.</p>
        <?php endif; ?>
        <a href="employees.php" class="back-link">Back to Employee List</a>
    </div>
    </main>
</body>
</html>