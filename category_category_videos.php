<?php
require 'config.php';
require 'helpers.php';

// Videos for selected category
// Endpoint: /categoryid/videos
// Method: GET

requireMethod('GET');

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/categoryid/videos",
    "description": "Videos for selected category"
});
?>