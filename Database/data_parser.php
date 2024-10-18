<?php
require_once 'db_connect.php';

$csvFile = 'factory_logs.csv';

if (($handle = fopen($csvFile, 'r')) !== false) {
    fgetcsv($handle);
    $latestEntries = [];

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        // Assigning CSV data to variables
        $machineName = trim($data[1]);
        $temperature = $data[2];
        $pressure = $data[3];
        $vibration = $data[4];
        $humidity = $data[5];
        $powerConsumption = $data[6];
        $status = $data[7];
        $errorCode = $data[8];
        $productionCount = $data[9];
        $maintenanceLog = $data[10];
        $speed = $data[11];

        $existingMachineID = getMachineIDByName($machineName, $pdo);

        // Generate a new 4-bit hexadecimal machine ID if this machine does not exist
        if ($existingMachineID === false) {
            $machineID = sprintf('%04X', rand(0, 65535));  // 4-bit hexadecimal
        } else {
            $machineID = $existingMachineID;
        }

        if (!isset($latestEntries[$machineName]) || true) {
            $latestEntries[$machineName] = [
                'machine_id' => $machineID,
                'machine_name' => $machineName,
                'status' => $status,
                'maintenance_log' => $maintenanceLog,
                'error_code' => $errorCode,
                'temperature' => $temperature,
                'pressure' => $pressure,
                'vibration' => $vibration,
                'humidity' => $humidity,
                'power_consumption' => $powerConsumption,
                'production_count' => $productionCount,
                'speed' => $speed
            ];
        }
    }

    fclose($handle);

    // Insert or update the latest entries into the database
    insertLatestEntries(array_values($latestEntries), $pdo);

    header('Location: ../MachineManagement.php?status=added');
    exit();
} else {
    echo "Error opening the CSV file.";
}

// Function to get the machine ID by machine name
function getMachineIDByName($machineName, $pdo) {
    $sql = "SELECT machine_id FROM machine_data WHERE machine_name = :machine_name LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':machine_name' => $machineName]);

    return $stmt->fetchColumn() ?: false;
}

// Function to insert the latest entries into the database
function insertLatestEntries($latestEntries, $pdo) {
    $sql = "INSERT INTO machine_data (machine_id, machine_name, status, maintenance_log, error_code, temperature, pressure, vibration, humidity, power_consumption, production_count, speed) 
            VALUES (:machine_id, :machine_name, :status, :maintenance_log, :error_code, :temperature, :pressure, :vibration, :humidity, :power_consumption, :production_count, :speed)
            ON DUPLICATE KEY UPDATE 
                status = VALUES(status), 
                maintenance_log = VALUES(maintenance_log), 
                error_code = VALUES(error_code),
                temperature = VALUES(temperature),
                pressure = VALUES(pressure),
                vibration = VALUES(vibration),
                humidity = VALUES(humidity),
                power_consumption = VALUES(power_consumption),
                production_count = VALUES(production_count),
                speed = VALUES(speed)";

    $stmt = $pdo->prepare($sql);

    foreach ($latestEntries as $entry) {
        $stmt->execute([
            ':machine_id' => $entry['machine_id'],
            ':machine_name' => $entry['machine_name'],
            ':status' => $entry['status'],
            ':maintenance_log' => $entry['maintenance_log'],
            ':error_code' => $entry['error_code'],
            ':temperature' => $entry['temperature'],
            ':pressure' => $entry['pressure'],
            ':vibration' => $entry['vibration'],
            ':humidity' => $entry['humidity'],
            ':power_consumption' => $entry['power_consumption'],
            ':production_count' => $entry['production_count'],
            ':speed' => $entry['speed']
        ]);
    }
}
?>
