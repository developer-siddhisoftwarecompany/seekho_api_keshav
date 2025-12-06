<?php
require 'config.php';
require 'helpers.php';

// Verify OTP
// Endpoint: /auth/verify-otp
// Method: POST

requireMethod('POST');
$data  = getJsonInput();
$email = trim($data['email'] ?? '');
$otp   = intval($data['otp'] ?? 0);

if ($email === '' || !$otp) {
    sendResponse(false, 'Email and OTP are required', null, 400);
}

$stmt = $conn->prepare('SELECT id, reset_otp, reset_otp_expires FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    sendResponse(false, 'Invalid email', null, 404);
}

$user = $result->fetch_assoc();
$stmt->close();

if ($user['reset_otp'] != $otp) {
    sendResponse(false, 'Invalid OTP', null, 400);
}

if (strtotime($user['reset_otp_expires']) < time()) {
    sendResponse(false, 'OTP expired', null, 400);
}

sendResponse(true, 'OTP verified. You can now reset password.');
?>
