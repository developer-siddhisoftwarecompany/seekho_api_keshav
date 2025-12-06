<?php
require 'config.php';
require 'helpers.php';

// Like a video
// Endpoint: /video/like
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);  // Authenticated user info

$data = getJsonInput();
// TODO: Validate $data and perform DB operations as needed
sendResponse(true, 'Stub POST response', {
    "example": true,
    "endpoint": "/video/like",
    "description": "Like a video",
    "received_body": "echoed for testing"
});
?>