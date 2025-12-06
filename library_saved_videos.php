<?php
require 'config.php';
require 'helpers.php';

// Saved videos list
// Endpoint: /library/saved
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);  // Authenticated user info

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/library/saved",
    "description": "Saved videos list"
});
?>