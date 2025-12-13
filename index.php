<?php
// Root Router for Word Tracker
// Redirects requests like /login.php to backend-php/api/login.php

// ---------------------------------------------------------------------------
// 1. CORS & Preflight Handling (Unified)
// ---------------------------------------------------------------------------
// Allow from any origin (or specific Angular port) dynamically
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Max-Age: 86400"); // Cache for 1 day
} else {
    // Fallback for non-browser checks
    header("Access-Control-Allow-Origin: *");
}

// Access-Control headers are needed for both Preflight and Actual requests
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle Preflight Options Request immediately
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// ---------------------------------------------------------------------------
// 2. Routing Logic
// ---------------------------------------------------------------------------

// Get the requested file name
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$filename = basename($path);

// Security: Prevent directory traversal
if (strpos($filename, '..') !== false) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid path"]);
    exit();
}

// If no filename (root), return welcome
if (empty($filename) || $filename == 'word-tracker') {
    echo json_encode([
        "message" => "Word Tracker API Root",
        "status" => "Running",
        "documentation" => "Access endpoints like /login or /register"
    ]);
    exit();
}

// Add .php extension if missing
if (strpos($filename, '.') === false) {
    $filename .= '.php';
}

// Define paths to search for the script
$searchPaths = [
    __DIR__ . '/backend-php/api/' . $filename,  // Priority 1: API folder
    __DIR__ . '/backend-php/' . $filename       // Priority 2: Backend Root
];

$foundPath = null;
foreach ($searchPaths as $p) {
    if (file_exists($p)) {
        $foundPath = $p;
        break;
    }
}

if ($foundPath) {
    // 3. Execution
    // Fix relative includes: scripts often rely on relative paths (e.g. ../config).
    // changing directory to the script's directory ensures consistency.
    chdir(dirname($foundPath));

    // Safely require the file
    // Note: Since headers are already sent above, scripts should checkheaders_sent() 
    // or use logic that doesn't conflict. 
    // We updated config/cors.php to respect existing headers.
    require $foundPath;
} else {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Endpoint not found: " . $filename,
        "searched_locations" => $searchPaths
    ]);
}
?>