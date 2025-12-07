<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/verify-otp
// Method: POST

requireMethod('POST');

$data  = getJsonInput();
$email = trim($data['email'] ?? '');
$otp   = intval($data['otp'] ?? 0);

if ($email === '' || !$otp) {
    sendResponse(false, 'Email and OTP are required', null, 400);
}

// Fetch user with PDO
$stmt = $conn->prepare("
    SELECT id, reset_otp, reset_otp_expires 
    FROM users 
    WHERE email = :email 
    LIMIT 1
");
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    sendResponse(false, 'Invalid email', null, 404);
}

// Validate OTP matches
if ((int)$user['reset_otp'] !== $otp) {
    sendResponse(false, 'Invalid OTP', null, 400);
}

// Validate expiration
if ($user['reset_otp_expires'] && strtotime($user['reset_otp_expires']) < time()) {
    sendResponse(false, 'OTP expired', null, 400);
}

sendResponse(true, 'OTP verified. You can now reset password.');
?>
