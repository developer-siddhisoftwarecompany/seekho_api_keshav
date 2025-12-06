<?php
require 'config.php';
require 'helpers.php';

// Logout user
// Endpoint: /auth/logout
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

// Clear the token
$stmt = $conn->prepare('UPDATE users SET api_token = NULL WHERE id = ?');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$stmt->close();

sendResponse(true, 'Logout successful');
?>
