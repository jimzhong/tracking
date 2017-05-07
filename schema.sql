CREATE TABLE devices
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name CHAR(32) NOT NULL,
  devaddr CHAR(10) NOT NULL,
  secret VARCHAR(255),
) CHARSET=utf8mb4;

CREATE TABLE locations
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    device_id INT NOT NULL,
    sampled_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL,
    lat double(10, 7),
    lon double(10, 7),
    speed double(8, 2),
    heading double(8, 2),
    battery INT,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE
) CHARSET=utf8mb4;

CREATE TABLE alerts
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dismissed TINYINT(1) NOT NULL DEFAULT 0,
  type CHAR(10) NOT NULL,
  device_id INT NOT NULL,
  location_id INT NULL,
  rule_id INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (device_id) REFERENCES devices (id) ON DELETE CASCADE,
  FOREIGN KEY (location_id) REFERENCES locations (id) ON DELETE CASCADE
) CHARSET=utf8mb4;


CREATE TABLE rules
(
  id INT PRIMARY KEY AUTO_INCREMENT,
  device_id INT NOT NULL UNIQUE,
  name VARCHAR(32) NOT NULL,
  lat double(10,7) NOT NULL,
  lon double(10,7) NOT NULL,
  radius double(10,2) NOT NULL,
  max_speed double(10,2) NOT NULL,
  FOREIGN KEY (device_id) REFERENCES devices (id) ON DELETE CASCADE
) CHARSET=utf8mb4;
