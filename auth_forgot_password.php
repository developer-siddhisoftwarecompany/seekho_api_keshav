<?php
require 'config.php';
require 'helpers.php';

// Method check
requireMethod('POST');

$data  = getJsonInput();
$email = trim($data['email'] ?? '');

if ($email === '') {
    sendResponse(false, 'Email is required', null, 400);
}

// Check user existence
$stmt = $conn->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmt->bindValue(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    sendResponse(false, 'No account with this email', null, 404);
}

$user_id = $user['id'];

// Generate OTP
$otp = rand(100000, 999999);

// Save OTP + expiry
$stmt = $conn->prepare("
    UPDATE users 
    SET reset_otp = :otp,
        reset_otp_expires = DATE_ADD(NOW(), INTERVAL 15 MINUTE) 
    WHERE id = :id
");

$stmt->bindValue(':otp', $otp);
$stmt->bindValue(':id', $user_id);
$stmt->execute();

// Tip: send via email with PHPMailer later

sendResponse(true, 'OTP sent to registered email', [
    'demo_otp' => $otp
]);
?>
