<?php
require 'config.php';
require 'helpers.php';

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

// Hash password securely
$hashed = password_hash($password, PASSWORD_BCRYPT);

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmt->bindValue(':email', $email);
$stmt->execute();

if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendResponse(false, 'Email already registered', null, 409);
}

// Insert new user
$stmt = $conn->prepare("
    INSERT INTO users (name, email, password) 
    VALUES (:name, :email, :password)
");

$stmt->bindValue(':name', $name);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':password', $hashed);

if ($stmt->execute()) {

    $user_id = $conn->lastInsertId();

    sendResponse(true, 'Signup successful', [
        'user_id' => (int) $user_id,
        'email'   => $email,
        'name'    => $name
    ]);

} else {
    sendResponse(false, 'Signup failed', null, 500);
}
?>
