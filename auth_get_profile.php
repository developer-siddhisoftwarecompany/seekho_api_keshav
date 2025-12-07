<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /auth/profile
// Method: GET

requireMethod('GET');

// requireAuth() MUST return a user array using PDO fetch
$user = requireAuth($conn);

sendResponse(true, 'Profile fetched', [
    'id'    => (int) $user['id'],
    'name'  => $user['name'],
    'email' => $user['email']
]);
?>
