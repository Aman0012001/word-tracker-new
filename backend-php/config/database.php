<?php
// backend-php/config/database.php

class Database
{
    // Use environment variables for Railway, fallback to localhost for local dev
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct()
    {
        // Database Configuration
        $this->host = getenv('MYSQLHOST') ?: '127.0.0.1';
        $this->port = getenv('MYSQLPORT') ?: '3306';
        $this->username = getenv('MYSQLUSER') ?: 'root';
        $this->password = getenv('MYSQLPASSWORD') ?: ''; // Empty for XAMPP
        $this->db_name = getenv('MYSQLDATABASE') ?: 'word_tracker';
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            // Log error for debugging
            error_log("Database connection error: " . $exception->getMessage());
            echo json_encode([
                "success" => false,
                "message" => "Database connection failed. Please check server configuration."
            ]);
            exit();
        }

        return $this->conn;
    }
}
?>