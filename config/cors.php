<?php
// backend-php/config/cors.php

function handleCors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Define allowed origins
        $allowedOrigins = [
            'http://localhost:4200',
            'http://localhost',
            'https://darkseagreen-alligator-228196.hostingersite.com',
            'https://darkseagreen-alligator-228196.hostingersite.com/', // With trailing slash
            'https://healthcheck.railway.app' // Allow Railway Healthchecks
        ];

        if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    // Ensure standard headers are present for non-OPTIONS requests too if needed
    // (Some browsers strictly require Allow-Headers even on simple requests if Authorization is involved)
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}
?>