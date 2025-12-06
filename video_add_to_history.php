<?php
require 'config.php';
require 'helpers.php';

// Add video to user history
// Endpoint: /video/add-history
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);  // Authenticated user info

$data = getJsonInput();
// TODO: Validate $data and perform DB operations as needed
sendResponse(true, 'Stub POST response', {
    "example": true,
    "endpoint": "/video/add-history",
    "description": "Add video to user history",
    "received_body": "echoed for testing"
});
?>