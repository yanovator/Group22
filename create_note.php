<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task Notes</title>

    <!-- Link to custom CSS for styling -->
    <link rel="stylesheet" href="style.css"> 

    <!-- Include Select2 CSS for enhanced dropdown functionality -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Include jQuery for Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Include Select2 JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>
<body>

    <!-- Main content section with Task Notes Form -->
    <main class="main-content">
        <h1>Create Task Notes</h1>

        <!-- Form for submitting task notes -->
        <form action="submit_note.php" method="POST" id="taskNoteForm">
            
            <!-- Observation text area with placeholder -->
            <label for="observation">Observation: 
                <small>(Describe what you observed)</small>
            </label>
            <textarea id="observation" name="observation" rows="4" required placeholder="Enter your observations here, e.g., machine malfunction, safety concern"></textarea>

            <!-- Factory Manager dropdown with employee ID and name -->
            <label for="manager">Assign to Factory Manager: 
                <small>(Select the manager responsible by name or employee ID)</small>
            </label>
            <select id="manager" name="manager" required>
                <option value="">Select a Factory Manager</option> <!-- Default option with placeholder -->
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
    </main>

    <!-- Initialize Select2 for the dropdown with placeholder for search bar -->
    <script>
    $(document).ready(function() {
        $('#manager').select2({
            placeholder: "Select a Factory Manager (ID or Name)", // Placeholder text for the dropdown
            allowClear: true // Allow the user to clear the selection
        });

        // Add placeholder to the search input inside the dropdown
        $('#manager').on('select2:open', function () {
            $('.select2-search__field').attr('placeholder', 'Search by ID or Name'); // Add placeholder to search bar
        });
    });
    </script>

</body>
</html>
