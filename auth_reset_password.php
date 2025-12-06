<?php
require 'config.php';
require 'helpers.php';

// Reset user password
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

$stmt = $conn->prepare('UPDATE users SET password = ?, reset_otp = NULL, reset_otp_expires = NULL WHERE email = ?');
$stmt->bind_param('ss', $hashed, $email);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    $stmt->close();
    sendResponse(true, 'Password reset successful');
} else {
    $stmt->close();
    sendResponse(false, 'Password reset failed or email not found', null, 400);
}
?>
