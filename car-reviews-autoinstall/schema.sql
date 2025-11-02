-- AWARDSPACE: wybierz swoją DB po lewej w phpMyAdmin, potem uruchom ten skrypt.
-- BEZ CREATE DATABASE/USE!

CREATE TABLE IF NOT EXISTS reviews (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  make VARCHAR(100) NOT NULL,
  model VARCHAR(100) NOT NULL,
  year SMALLINT UNSIGNED NOT NULL,
  fuel ENUM('petrol','diesel','hybrid','electric') NOT NULL,
  `usage` ENUM('owned','rented','testdrive','other') NOT NULL,
  gearbox ENUM('manual','auto') NOT NULL,
  `condition` ENUM('excellent','good','fair','poor') NOT NULL,
  bodytype ENUM('sedan','kombi','hatchback','suv','coupe','van') NOT NULL,
  rating TINYINT UNSIGNED NOT NULL,
  comment TEXT,
  author VARCHAR(120),
  date DATE NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  INDEX idx_make_model_year (make, model, year),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
