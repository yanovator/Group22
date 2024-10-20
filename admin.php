<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT user, action, timestamp FROM user_activity";
$result = $conn->query($sql);

$user_activity = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_activity[] = $row;
    }
}
$conn->close();

// Output JSON
echo json_encode($user_activity);
?>
<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "dashboard");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT date, user, access_type, status FROM access_logs";
$result = $conn->query($sql);

$access_logs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $access_logs[] = $row;
    }
}
$conn->close();

// Output JSON
echo json_encode($access_logs);
?>