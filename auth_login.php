<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/login
// Method: POST

requireMethod('POST');
$data = getJsonInput();

$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if ($email === '' || $password === '') {
    sendResponse(false, 'Email and password are required', null, 400);
}

// Check user
$stmt = $conn->prepare(
    "SELECT id, name, email, password 
     FROM users 
     WHERE email = :email 
     LIMIT 1"
);
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    sendResponse(false, 'Invalid email or password', null, 401);
}

// Verify password
if (!password_verify($password, $user['password'])) {
    sendResponse(false, 'Invalid email or password', null, 401);
}

// Generate API token
$token = bin2hex(random_bytes(32));

// Save token
$stmt = $conn->prepare("UPDATE users SET api_token = :token WHERE id = :id");
$stmt->bindValue(':token', $token);
$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
$stmt->execute();

// Success response
sendResponse(true, 'Login successful', [
    'token' => $token,
    'user'  => [
        'id'    => (int) $user['id'],
        'name'  => $user['name'],
        'email' => $user['email']
    ]
]);
?>
