<?php
// backend-php/config/cors.php

function handleCors()
{
    $allowed_origins = [
        'http://localhost:4200'
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    // If no origin, logic flow continues, but we might want to default for tools like Postman if not strict
    if (in_array($origin, $allowed_origins) || empty($origin)) {
        header("Access-Control-Allow-Origin: " . ($origin ?: '*'));
    } else {
        // Optional: Allow * for development if consistent with security posture
        header("Access-Control-Allow-Origin: *");
    }

    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');

    // preflight
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        exit(0);
    }
}
?>