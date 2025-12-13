<?php
// Quick database setup script
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== Database Setup Script ===\n\n";

// Connect to MySQL (without database)
try {
    $pdo = new PDO("mysql:host=127.0.0.1;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected to MySQL\n";

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS word_tracker");
    echo "✅ Database 'word_tracker' ready\n";

    // Select the database
    $pdo->exec("USE word_tracker");

    // Check if users table exists
    $result = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() == 0) {
        echo "⚠️  Users table not found. Creating...\n";

        // Create users table
        $pdo->exec("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        echo "✅ Users table created\n";

        // Create a test user
        $testPassword = password_hash('test123', PASSWORD_DEFAULT);
        $pdo->exec("
            INSERT INTO users (username, email, password_hash) 
            VALUES ('testuser', 'test@example.com', '$testPassword')
        ");
        echo "✅ Test user created (email: test@example.com, password: test123)\n";
    } else {
        echo "✅ Users table exists\n";

        // Check if test user exists
        $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'test@example.com'");
        if ($stmt->fetchColumn() == 0) {
            $testPassword = password_hash('test123', PASSWORD_DEFAULT);
            $pdo->exec("
                INSERT INTO users (username, email, password_hash) 
                VALUES ('testuser', 'test@example.com', '$testPassword')
            ");
            echo "✅ Test user created (email: test@example.com, password: test123)\n";
        } else {
            echo "ℹ️  Test user already exists\n";
        }
    }

    echo "\n=== Setup Complete! ===\n";
    echo "You can now login with:\n";
    echo "Email: test@example.com\n";
    echo "Password: test123\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>