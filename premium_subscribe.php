<?php
require 'config.php';
require 'helpers.php';

// Subscribe to premium
// Endpoint: /premium/subscribe
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);  // Authenticated user info

$data = getJsonInput();
// TODO: Validate $data and perform DB operations as needed
sendResponse(true, 'Stub POST response', {
    "example": true,
    "endpoint": "/premium/subscribe",
    "description": "Subscribe to premium",
    "received_body": "echoed for testing"
});
?>