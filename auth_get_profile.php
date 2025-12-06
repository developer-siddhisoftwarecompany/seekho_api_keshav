<?php
require 'config.php';
require 'helpers.php';

// Fetch user profile
// Endpoint: /auth/profile
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);

sendResponse(true, 'Profile fetched', [
    'id'    => $user['id'],
    'name'  => $user['name'],
    'email' => $user['email']
]);
?>
