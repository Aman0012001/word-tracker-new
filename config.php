<?php
// Enable error reporting for debugging (Remove in full production if needed, but useful now)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS Headers
$corsConfigPath = __DIR__ . '/config/cors.php';
if (file_exists($corsConfigPath)) {
    require_once $corsConfigPath;
    handleCors();
} else {
    // Fallback
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
}

// Database connection
class Database
{
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
        $this->password = getenv('MYSQLPASSWORD') ?: '';
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
            // Return JSON error with connection details
            http_response_code(500);
            header("Content-Type: application/json");
            echo json_encode([
                "status" => "error",
                "message" => "Database Connection Failed: " . $exception->getMessage()
            ]);
            exit();
        }
        return $this->conn;
    }
}
?>