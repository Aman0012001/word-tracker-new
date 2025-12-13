<?php
// backend-php/index.php

require_once 'config/cors.php';
require_once 'config/database.php';

handleCors();

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Universal Router
// Routes requests from /word-tracker/endpoint.php to /backend-php/api/endpoint.php
// OR /endpoint.php to api/endpoint.php if served from root

$path = parse_url($request_uri, PHP_URL_PATH);
$filename = basename($path); // e.g. login.php or login

// If no extension, assume .php
if (strpos($filename, '.') === false) {
    $filename .= '.php';
}

// Security: Prevent directory traversal
$filename = basename($filename);

$apiFile = __DIR__ . '/api/' . $filename;

if (file_exists($apiFile)) {
    require $apiFile;
} else {
    // Check if it's a known mapping (optional, for backward compatibility)
    // For now, return 404
    http_response_code(404);
    echo json_encode([
        "message" => "Endpoint not found: " . $filename,
        "debug_path" => $apiFile
    ]);
}
?>