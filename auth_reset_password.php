<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/reset-password
// Method: POST

requireMethod('POST');
$data         = getJsonInput();
$email        = trim($data['email'] ?? '');
$new_password = trim($data['new_password'] ?? '');

if ($email === '' || $new_password === '') {
    sendResponse(false, 'Email and new_password are required', null, 400);
}

$hashed = password_hash($new_password, PASSWORD_BCRYPT);

// Update password, clear OTP
$stmt = $conn->prepare("
    UPDATE users 
    SET password = :password,
        reset_otp = NULL,
        reset_otp_expires = NULL
    WHERE email = :email
");

$stmt->bindValue(':password', $hashed);
$stmt->bindValue(':email', $email);
$stmt->execute();

// PDO affected rows:
if ($stmt->rowCount() > 0) {
    sendResponse(true, 'Password reset successful');
} else {
    sendResponse(false, 'Password reset failed or email not found', null, 400);
}
?>
