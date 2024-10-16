<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $id = $_POST['id'] ?? '';
    $role = $_POST['role'] ?? '';

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert employee details into the database
    $sql = "INSERT INTO employees (name, id, role) VALUES ('$name', '$id', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>New employee added successfully: $name (ID: $id, Role: $role)</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Callum Forbes">
    <title>Add New Employee</title>
    <link rel="stylesheet" href="./Styles/styles.css">
</head>
<body>
    <h2>Add New Employee</h2>
    <form action="AddEmployee.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="id">ID:</label>
        <input type="number" id="id" name="id" required><br><br> 

        <label for="role">Role:</label>
        <input type="text" id="role" name="role" required><br><br>

        <button type="submit">Add Employee</button>
    </form>

    <br>
    <a href="employees.php">Back to Employee List</a>
</body>
</html>