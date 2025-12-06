<?php
// helpers.php - Common helper functions for all APIs

// Always return JSON
header("Content-Type: application/json");

// CORS headers (optional, useful for mobile / web apps)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization, App-Version, Platform");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Read JSON body
function getJsonInput() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        $data = [];
    }
    return $data;
}

// Get a specific header (case-insensitive)
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

    // Lookup user by token
    $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE api_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        sendResponse(false, "Invalid or expired token", null, 401);
    }

    $user = $result->fetch_assoc();
    $stmt->close();
    return $user; // return user row
}
?>
