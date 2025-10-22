<?php
// === DOMYŚLNA KONFIGURACJA DLA XAMPP ===
const DB_HOST = 'localhost';
const DB_NAME = 'car_reviews';
const DB_USER = 'root';
const DB_PASS = '';

function pdo_connect($dbname = null) {
  $dsn = 'mysql:host='.DB_HOST.';charset=utf8mb4' . ($dbname ? ';dbname='.$dbname : '');
  return new PDO($dsn, DB_USER, DB_PASS, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
}
function ensure_db_and_table() {
  // ensure DB
  try {
    $pdo = pdo_connect(DB_NAME);
  } catch (Throwable $e) {
    $root = pdo_connect(null);
    $root->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = pdo_connect(DB_NAME);
  }
  // ensure table
  $sql = "CREATE TABLE IF NOT EXISTS reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    make  VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year  SMALLINT NOT NULL,
    fuel  ENUM('petrol','diesel','hybrid','electric') NOT NULL,
    `usage` ENUM('owned','rented','testdrive','other') NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT,
    author VARCHAR(100),
    date DATE,
    INDEX idx_created_at (created_at),
    INDEX idx_make_model_year (make, model, year)
  )
  ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;";
  $pdo->exec($sql);
  return $pdo;
}
function json_headers() {
  header('Content-Type: application/json; charset=utf-8');
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Content-Type, Accept');
  header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
}
