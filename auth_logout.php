<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/logout
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

// Clear token
$stmt = $conn->prepare("UPDATE users SET api_token = NULL WHERE id = :id");
$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
$stmt->execute();

sendResponse(true, 'Logout successful');
?>
