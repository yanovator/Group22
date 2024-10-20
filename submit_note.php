<?php
session_start(); // Start session if needed for user authentication

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecting the form inputs
    $observation = $_POST['observation'];
    $manager_id = $_POST['manager'];

    // Assuming these are fetched from the database (you can modify the query to get them)
    $conn = new mysqli('localhost', 'root', '', 'factory_db');

    // Check if the connection is successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch manager details (name and employee_id) based on manager_id
    $query = "SELECT name, employee_id FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $manager_id);
    $stmt->execute();
    $stmt->bind_result($manager_name, $employee_id);
    $stmt->fetch();
    $stmt->close();

    // Insert the task note into the database
    $sql = "INSERT INTO task_notes (observation, factory_manager_name, factory_manager_employee_id, created_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $observation, $manager_name, $employee_id);

    // Check if the statement was successful
    if ($stmt->execute()) {
        $message = "Task note submitted successfully!";
    } else {
        $message = "Oops! Something went wrong. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Task Note</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>Submit Task Notes</h1>

        <?php if (isset($message)): ?>
            <div class="message success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <a href="create_note.php" class="btn">Back to Form</a>
    </div>

</body>
</html>
