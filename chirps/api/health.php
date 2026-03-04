<?php

require_once('../config.php');

header('Content-Type: application/json; charset=utf-8');

$response = array(
    'status' => 'ok',
    'app' => 'chirps-api',
    'timestamp' => gmdate('c'),
    'php_version' => PHP_VERSION,
    'session_active' => session_status() === PHP_SESSION_ACTIVE,
    'debug_mode' => DEBUG_MODE,
);

echo json_encode($response);
