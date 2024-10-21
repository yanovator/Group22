CREATE DATABASE IF NOT EXISTS factory_logs;

USE factory_logs;

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
    operator VARCHAR(255) DEFAULT NULL,
    machineComments TEXT,
    createdTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (machine_id),
    UNIQUE (machine_id) 
);

CREATE TABLE Jobs(
    jobID VARCHAR(10) NOT NULL PRIMARY KEY,
    jobTitle varchar(100) NOT NULL,
    jobStatus ENUM ('In Progress', 'Completed', 'Waiting Parts'),
    location varchar(100) NOT NULL,
    jobComments TEXT,
    createdTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

CREATE DATABASE IF NOT EXISTS employee;

USE employee;

CREATE TABLE IF NOT EXISTS employees (
  id int(8) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  role VARCHAR(255) NOT NULL,
  Email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL
);
