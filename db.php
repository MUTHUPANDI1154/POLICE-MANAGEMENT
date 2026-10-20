<?php
// db.php - Database Tier Connection for 3-Tier PHP Police Examination System

$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP MySQL password is empty
$dbname = 'police_exam_db';

$conn = null;

try {
    // Connect to MySQL server
    $conn = new mysqli($host, $user, $pass);

    if (!$conn->connect_error) {
        // Create database if not exists
        $conn->query("CREATE DATABASE IF NOT EXISTS `$dbname`");
        $conn->select_db($dbname);

        // Create table for storing candidate police examination results & mark list
        $tableSql = "CREATE TABLE IF NOT EXISTS `police_exam_results` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `app_number` VARCHAR(50) NOT NULL,
            `candidate_name` VARCHAR(100) NOT NULL,
            `category` VARCHAR(100) NOT NULL,
            `score` INT NOT NULL,
            `total_questions` INT NOT NULL,
            `submission_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($tableSql);
    }
} catch (Exception $e) {
    // Database connection silent fallback
    $conn = null;
}

function getDbConnection() {
    global $conn;
    return $conn;
}
?>
