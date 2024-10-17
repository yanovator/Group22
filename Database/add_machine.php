<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $machineName = $_POST['machine_name'];
    $operator = $_POST['operator'];
    $status = $_POST['status'];

    $machineId = generateUniqueMachineId($pdo);

    $stmt = $pdo->prepare("INSERT INTO machine_data (machine_id, machine_name, operator, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$machineId, $machineName, $operator, $status]);
    
    header('Location: ../MachineManagement.php?status=added');
    exit();
}

function generateUniqueMachineId($pdo) {
    do {
        $id = strtoupper(dechex(rand(0, 65535)));
        $id = str_pad($id, 4, '0', STR_PAD_LEFT);
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM machine_data WHERE machine_id = ?");
        $stmt->execute([$id]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    
    return $id;
}

?>