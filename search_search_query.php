<?php
require 'config.php';
require 'helpers.php';

// Search videos
// Endpoint: /search?query=
// Method: GET

requireMethod('GET');

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/search?query=",
    "description": "Search videos"
});
?>