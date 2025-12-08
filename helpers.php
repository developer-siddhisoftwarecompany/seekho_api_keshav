<?php
// helpers.php

// Always return JSON
header("Content-Type: application/json");

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, App-Version, Platform");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Read JSON OR form body (WITHOUT requiring Content-Type header)
function getJsonInput() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    // JSON failed or empty → fallback to POST
    if (!is_array($data) || empty($data)) {
        $data = $_POST;
    }

    return $data;
}

// Get header (case-insensitive)
function getHeader($name) {
    $headers = getallheaders();
    foreach ($headers as $key => $value) {
        if (strtolower($key) === strtolower($name)) {
            return $value;
        }
    }
    return null;
}

// Unified JSON response
function sendResponse($status, $message, $data = null, $code = 200) {
    http_response_code($code);
    $resp = [
        "status"  => $status,
        "message" => $message,
    ];
    if (!is_null($data)) {
        $resp["data"] = $data;
    }
    echo json_encode($resp);
    exit();
}

// Require specific HTTP method
function requireMethod($method) {
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        sendResponse(false, "Method not allowed. Use $method.", null, 405);
    }
}

// Require Authorization: Bearer <token>
function requireAuth($conn) {

    $authHeader = getHeader("Authorization");
    if (!$authHeader) {
        sendResponse(false, "Missing Authorization header", null, 401);
    }

    if (stripos($authHeader, "Bearer ") !== 0) {
        sendResponse(false, "Invalid Authorization format", null, 401);
    }

    $token = trim(substr($authHeader, 7));
    if ($token === "") {
        sendResponse(false, "Token missing", null, 401);
    }

    // Lookup user by token with PDO
    $stmt = $conn->prepare("
        SELECT id, name, email 
        FROM users 
        WHERE api_token = :token 
        LIMIT 1
    ");
    $stmt->bindValue(':token', $token);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        sendResponse(false, "Invalid or expired token", null, 401);
    }

    return $user;
}
?>
