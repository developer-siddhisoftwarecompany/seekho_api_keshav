<?php
require 'config.php';
require 'helpers.php';

// Get homepage top videos
// Endpoint: /home/top-videos
// Method: GET

requireMethod('GET');

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/home/top-videos",
    "description": "Get homepage top videos"
});
?>