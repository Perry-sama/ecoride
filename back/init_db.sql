-- back/init_db.sql
-- Script rédigé manuellement pour init base de données EcoRide
CREATE DATABASE IF NOT EXISTS `ecoride` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecoride`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(180) NOT NULL UNIQUE,
  `roles` JSON NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vehicle` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `brand` VARCHAR(100),
  `model` VARCHAR(100),
  `electric` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_vehicle_user FOREIGN KEY (user_id) REFERENCES `user`(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ride` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `driver_id` INT,
  `departure` VARCHAR(255),
  `arrival` VARCHAR(255),
  `date_time` DATETIME,
  `seats` INT DEFAULT 1,
  `price` DECIMAL(6,2) DEFAULT 0.00,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ride_driver FOREIGN KEY (driver_id) REFERENCES `user`(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_ride_departure ON ride(departure);
CREATE INDEX idx_ride_arrival ON ride(arrival);

INSERT INTO `user` (email, roles, password, is_active) VALUES
('demo@ecoride.test', '["ROLE_USER"]', 'demo_hash', 1);
