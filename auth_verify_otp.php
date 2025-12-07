<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/verify-otp
// Method: POST

requireMethod('POST');

$data = getJsonInput();

$email = trim($data['email'] ?? '');
$otp   = trim($data['otp']   ?? '');

if ($email === '' || $otp === '') {
    sendResponse(false, 'Email and OTP are required', null, 400);
}

// Find matching user and valid OTP
$stmt = $conn->prepare("
    SELECT id 
    FROM users 
    WHERE email = :email 
      AND reset_otp = :otp 
      AND reset_otp_expires > NOW()
    LIMIT 1
");
$stmt->bindValue(':email', $email);
$stmt->bindValue(':otp', $otp);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    sendResponse(false, 'Invalid or expired OTP', null, 400);
}

// Clear OTP after success
$stmt = $conn->prepare("
    UPDATE users
    SET reset_otp = NULL,
        reset_otp_expires = NULL
    WHERE id = :id
");
$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
$stmt->execute();

sendResponse(true, 'OTP verified successfully');
?>
