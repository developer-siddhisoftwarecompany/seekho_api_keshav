<?php
require 'config.php';
require 'helpers.php';

// Verify OTP
// Endpoint: /auth/verify-otp
// Method: POST

requireMethod('POST');

$data = getJsonInput();
// TODO: Validate $data and perform DB operations as needed
sendResponse(true, 'Stub POST response', {
    "example": true,
    "endpoint": "/auth/verify-otp",
    "description": "Verify OTP",
    "received_body": "echoed for testing"
});
?>