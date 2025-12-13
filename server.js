const http = require('http');
const mysql = require('mysql2/promise');

// Database configuration with hardcoded credentials as requested
const dbConfig = {
    host: "shuttle.proxy.rlwy.net",
    user: "root",
    password: "WiGhctjnxmSBDWukfTiCLzvLGrXRmQdt",
    database: "railway",
    port: 36666,
    multipleStatements: true,
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0,
    enableKeepAlive: true,
    keepAliveInitialDelay: 0
};

// Database schema
const schema = `
-- Word Tracker Database Schema
-- Database: word_tracker

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS word_tracker;
USE word_tracker;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plans table (updated schema)
CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    content_type VARCHAR(100) NULL,
    activity_type VARCHAR(100) NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    goal_amount INT NOT NULL DEFAULT 0,
    strategy VARCHAR(50) DEFAULT 'steady',
    intensity VARCHAR(50) DEFAULT 'average',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Plan Days table (daily schedule)
CREATE TABLE IF NOT EXISTS plan_days (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_id INT NOT NULL,
    date DATE NOT NULL,
    target INT NOT NULL DEFAULT 0,
    logged INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE CASCADE,
    UNIQUE KEY unique_plan_date (plan_id, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Checklists table
CREATE TABLE IF NOT EXISTS checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_id INT NULL,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Checklist Items table
CREATE TABLE IF NOT EXISTS checklist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    checklist_id INT NOT NULL,
    item_text TEXT NOT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
`;

let pool;

// Unhandled rejection handler
process.on('unhandledRejection', (reason, promise) => {
    console.error('Unhandled Rejection at:', promise, 'reason:', reason);
});

// Graceful shutdown
const shutdown = async () => {
    console.log('Shutting down...');
    if (pool) {
        try {
            await pool.end();
            console.log('Database pool closed.');
        } catch (err) {
            console.error('Error closing database pool:', err);
        }
    }
    process.exit(0);
};

process.on('SIGTERM', shutdown);
process.on('SIGINT', shutdown);

async function initializeDatabase() {
    try {
        console.log('Starting execution of database schema...');

        // Use a temporary connection for schema migration
        const connection = await mysql.createConnection(dbConfig);

        try {
            await connection.query(schema);
            console.log('Schema executed successfully.');
        } finally {
            await connection.end();
        }
    } catch (err) {
        // Log error but don't crash if it's just about existing tables (handled by IF NOT EXISTS)
        // However, if connection fails, we should log and retry or exit.
        // For production-ready, we might want to exit so the orchestrator restarts, or keep retrying.
        // Here we log and exit to ensure "no crash on redeploy" implies controlled behavior.
        console.error('Database schema initialization error:', err.message);
        // If it's a connection error, we can't start.
        throw err;
    }
}

async function startApp() {
    try {
        await initializeDatabase();

        // Create connection pool for the server
        pool = mysql.createPool(dbConfig);

        // Verify pool connection
        // Note: The pool doesn't connect immediately, so we can run a test query.
        // Also, our schema uses 'USE word_tracker', but pool config has 'database: railway'.
        // We should probably check connectivity.
        await pool.query('SELECT 1');
        console.log('Database connection pool established.');

        const PORT = process.env.PORT || 3000;

        const server = http.createServer(async (req, res) => {
            // CORS Support
            res.setHeader('Access-Control-Allow-Origin', '*');
            res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
            res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

            if (req.method === 'OPTIONS') {
                res.writeHead(204);
                res.end();
                return;
            }

            // Health Check
            if (req.url === '/health' || req.url === '/status') {
                try {
                    await pool.query('SELECT 1');
                    res.writeHead(200, { 'Content-Type': 'application/json' });
                    res.end(JSON.stringify({ status: 'ok', uptime: process.uptime() }));
                } catch (err) {
                    console.error('Health check failed:', err.message);
                    res.writeHead(503, { 'Content-Type': 'application/json' });
                    res.end(JSON.stringify({ status: 'error', message: 'Database disconnected' }));
                }
                return;
            }

            // Default Response
            res.writeHead(404, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ message: 'Not Found' }));
        });

        server.listen(PORT, () => {
            console.log(`Server running on port ${PORT}`);
        });

    } catch (err) {
        console.error('Failed to start application:', err);
        process.exit(1);
    }
}

startApp();
