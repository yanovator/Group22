<?php
require_once 'db_connect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['machine_id'])) {
        $machineID = $_POST['machine_id'];

        $sql = "DELETE FROM machine_data WHERE machine_id = :machine_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([':machine_id' => $machineID]);

        if ($stmt->rowCount() > 0) {
            echo "Machine deleted successfully!";
        } else {
            echo "No machine found with that ID.";
        }
    } else {
        echo "No machine ID provided.";
    }

    header('Location: ../MachineManagement.php?status=deleted');
    exit();
}
?>