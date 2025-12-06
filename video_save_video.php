<?php
require 'config.php';
require 'helpers.php';

// Save video to library
// Endpoint: /video/save
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);  // Authenticated user info

$data = getJsonInput();
// TODO: Validate $data and perform DB operations as needed
sendResponse(true, 'Stub POST response', {
    "example": true,
    "endpoint": "/video/save",
    "description": "Save video to library",
    "received_body": "echoed for testing"
});
?>