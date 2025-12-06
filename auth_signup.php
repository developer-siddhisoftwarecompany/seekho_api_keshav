<?php
require 'config.php';
require 'helpers.php';

// Register new user
// Endpoint: /auth/signup
// Method: POST

requireMethod('POST');

$data = getJsonInput();

$name     = trim($data['name'] ?? '');
$email    = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if ($name === '' || $email === '' || $password === '') {
    sendResponse(false, 'Name, email and password are required', null, 400);
}

// Hash password
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Check if user exists
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    sendResponse(false, 'Email already registered', null, 409);
}
$stmt->close();

// Insert user
$stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hashed);

if ($stmt->execute()) {
    $user_id = $stmt->insert_id;
    $stmt->close();
    sendResponse(true, 'Signup successful', [
        'user_id' => $user_id,
        'email'   => $email,
        'name'    => $name
    ]);
} else {
    $err = $conn->error;
    $stmt->close();
    sendResponse(false, 'Signup failed: ' . $err, null, 500);
}
?>
