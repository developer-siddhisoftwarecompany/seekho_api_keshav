<?php
require 'config.php';
require 'helpers.php';

// Send OTP for reset
// Endpoint: /auth/forgot-password
// Method: POST

requireMethod('POST');
$data  = getJsonInput();
$email = trim($data['email'] ?? '');

if ($email === '') {
    sendResponse(false, 'Email is required', null, 400);
}

// Check if user exists
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    sendResponse(false, 'No account with this email', null, 404);
}

$user = $result->fetch_assoc();
$user_id = $user['id'];
$stmt->close();

// Generate OTP
$otp = rand(100000, 999999);

// Save OTP and expiry
$stmt = $conn->prepare('UPDATE users SET reset_otp = ?, reset_otp_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = ?');
$stmt->bind_param('ii', $otp, $user_id);
$stmt->execute();
$stmt->close();

// TODO: send OTP via email/SMS in production

sendResponse(true, 'OTP sent to registered email', [
    'demo_otp' => $otp // For testing only
]);
?>
