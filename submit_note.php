<?php
session_start(); // Start session for user authentication

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $observation = $_POST['observation'];
    $manager_id = $_POST['manager'];

    // Assuming the user's ID is stored in the session
    $operator_id = $_SESSION['user_id']; 

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'factory_db');

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert task note into database
    $sql = "INSERT INTO task_notes (observation, operator_id, manager_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sii', $observation, $operator_id, $manager_id);

    // Check if the statement was successful
    if ($stmt->execute()) {
        $message = "Task note submitted successfully!";
        $message_type = "success";
    } else {
        $message = "Oops! Something went wrong. Please try again.";
        $message_type = "error";
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
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
            <script>
                // Scroll to the message after submission
                document.addEventListener("DOMContentLoaded", function() {
                    document.querySelector('.message').scrollIntoView();
                });
            </script>
        <?php endif; ?>

        <a href=".php"create_note.php class="btn">Back to Form</a>
    </div>

</body>
</html>
