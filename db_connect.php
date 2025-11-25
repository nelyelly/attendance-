<?php
// db_connect.php - Database connection with error handling
require_once 'config.php';

function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        
        // Set error mode to exceptions
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        return $conn;
        
    } catch (PDOException $e) {
        // Log error to file
        $error_message = "[" . date('Y-m-d H:i:s') . "] Database connection failed: " . $e->getMessage() . "\n";
        file_put_contents('database_errors.log', $error_message, FILE_APPEND);
        
        // Display user-friendly message
        die("Connection failed: Unable to connect to the database. Please try again later.");
    }
}

function connectToDatabase() {
    return getDBConnection();
}

// ==================== CREATE DATABASE AND TABLES ====================
function createDatabaseAndTables() {
    try {
        // Connect without database to create it
        $dsn = "mysql:host=" . DB_HOST . ";charset=utf8";
        $conn = new PDO($dsn, DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if not exists
        $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        echo "<p style='color: green;'>✓ Database 'student_attendance' created successfully!</p>";
        
        // Switch to the database
        $conn->exec("USE " . DB_NAME);
        
        // Create students table (Exercise 4)
        $conn->exec("CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(100) NOT NULL,
            matricule VARCHAR(20) NOT NULL UNIQUE,
            group_id VARCHAR(10) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "<p style='color: green;'>✓ Table 'students' created successfully!</p>";
        
        // Create attendance_sessions table (Exercise 5)
        $conn->exec("CREATE TABLE IF NOT EXISTS attendance_sessions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            course_id VARCHAR(50) NOT NULL,
            group_id VARCHAR(10) NOT NULL,
            date DATE NOT NULL,
            opened_by INT NOT NULL,
            status ENUM('open', 'closed') DEFAULT 'open',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "<p style='color: green;'>✓ Table 'attendance_sessions' created successfully!</p>";
        
        $conn = null;
        return true;
        
    } catch (PDOException $e) {
        $error_message = "[" . date('Y-m-d H:i:s') . "] Database creation failed: " . $e->getMessage() . "\n";
        file_put_contents('database_errors.log', $error_message, FILE_APPEND);
        return false;
    }
}

// Auto-create database and tables when this file is included
createDatabaseAndTables();
?>