<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $machineID = $_POST['machine_id'];

        $operatorKey = 'operator_' . $machineID;
        $statusKey = 'status_' . $machineID;

        $operator = isset($_POST[$operatorKey]) ? trim($_POST[$operatorKey]) : '';
        $status = isset($_POST[$statusKey]) ? $_POST[$statusKey] : '';

        error_log("Updating Machine ID: $machineID, Operator: $operator, Status: $status");

        $sql = "UPDATE machine_data SET operator = :operator, status = :status WHERE machine_id = :machine_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':operator' => $operator, ':status' => $status, ':machine_id' => $machineID]);

        header('Location: ../FactoryManager.php');
        exit();
    }
}
?>
