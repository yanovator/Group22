<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $machineID = $_POST['machine_id'];

        $errorKey = 'errorCode_' . $machineID;

        $errorCode = isset($_POST[$errorKey]) ? trim($_POST[$errorKey]) : '';

        switch ($errorCode) {
            case 'E101':
                $maintenanceLog = "Routine Check";
                break;
            case 'E202':
                $maintenanceLog = "Software Update";
                break;
            case 'E303':
                $maintenanceLog = "Part Replacement";
                break;
            case 'E404':
                $maintenanceLog = "Catastrophic Failure";
                break;
            default:
                $maintenanceLog = "";
        }

        error_log("Updating Machine ID: $machineID, Error Code: $errorCode, Maintenance Log: $maintenanceLog");

        $sql = "UPDATE machine_data SET error_code = :error_code, maintenance_log = :maintenance_log WHERE machine_id = :machine_id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':error_code' => $errorCode,
            ':maintenance_log' => $maintenanceLog,
            ':machine_id' => $machineID
        ]);

        header('Location: ../MachineManagement.php');
        exit();
    }
}
?>