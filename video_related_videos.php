<?php
require 'config.php';
require 'helpers.php';

// Fetch related videos
// Endpoint: /video/related/id
// Method: GET

requireMethod('GET');

// TODO: Implement actual logic using $conn and any query parameters / headers
sendResponse(true, 'Stub GET response', {
    "example": true,
    "endpoint": "/video/related/id",
    "description": "Fetch related videos"
});
?>