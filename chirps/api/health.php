<?php

header('Content-Type: application/json; charset=utf-8');

$response = array(
    'status' => 'ok',
    'app' => 'chirps-api',
    'timestamp' => gmdate('c'),
    'php_version' => PHP_VERSION,
    'session_active' => false,
    'debug_mode' => false,
    'database_connected' => false,
);

try {
    require_once('../config.php');

    $response['session_active'] = session_status() === PHP_SESSION_ACTIVE;
    $response['debug_mode'] = defined('DEBUG_MODE') ? DEBUG_MODE : false;
    $response['database_connected'] = isset($DB) && $DB instanceof PDO;
} catch (Throwable $e) {
    http_response_code(503);
    $response['status'] = 'degraded';
    $response['error'] = 'Database connection failed';
}

echo json_encode($response);
