<?php
require 'config.php';
require 'helpers.php';

// User login
// Endpoint: /auth/login
// Method: POST

requireMethod('POST');
$data = getJsonInput();

$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if ($email === '' || $password === '') {
    sendResponse(false, 'Email and password are required', null, 400);
}

// Find user
$stmt = $conn->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    sendResponse(false, 'Invalid email or password', null, 401);
}

$user = $result->fetch_assoc();
$stmt->close();

// Verify password
if (!password_verify($password, $user['password'])) {
    sendResponse(false, 'Invalid email or password', null, 401);
}

// Generate token
$token = bin2hex(random_bytes(32));

// Save token
$stmt = $conn->prepare('UPDATE users SET api_token = ? WHERE id = ?');
$stmt->bind_param('si', $token, $user['id']);
$stmt->execute();
$stmt->close();

sendResponse(true, 'Login successful', [
    'token' => $token,
    'user'  => [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email']
    ]
]);
?>
