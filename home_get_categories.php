<?php
require 'config.php';
require 'helpers.php';

// Get home categories
// Endpoint: /home/categories
// Method: GET

requireMethod('GET');

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/home/categories",
    "description": "Get home categories"
});
?>