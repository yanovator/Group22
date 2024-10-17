CREATE TABLE IF NOT EXISTS machine_data (
    machine_id VARCHAR(10) NOT NULL,
    machine_name VARCHAR(100) NOT NULL,
    status VARCHAR(50),
    maintenance_log VARCHAR(255),
    error_code VARCHAR(50),
    temperature DECIMAL(5,2),
    pressure DECIMAL(5,2),
    vibration DECIMAL(5,2),
    humidity DECIMAL(5,2),
    power_consumption DECIMAL(8,2),
    production_count INT,
    speed DECIMAL(5,2),
    PRIMARY KEY (machine_id),
    UNIQUE (machine_id) 
);