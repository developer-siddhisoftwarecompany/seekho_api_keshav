<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /premium/subscribe
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

$data = getJsonInput();
$plan_id = intval($data['plan_id'] ?? 0);

if ($plan_id <= 0) {
    sendResponse(false, "plan_id required", null, 400);
}

// Fetch plan info
$stmt = $conn->prepare("
    SELECT id, duration_days 
    FROM plans 
    WHERE id = :pid
    LIMIT 1
");
$stmt->bindValue(':pid', $plan_id, PDO::PARAM_INT);
$stmt->execute();

$plan = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$plan) {
    sendResponse(false, "Invalid plan selected", null, 404);
}

// Update premium
$stmt = $conn->prepare("
    UPDATE users
    SET premium = 1,
        premium_expires = DATE_ADD(NOW(), INTERVAL :days DAY)
    WHERE id = :id
");
$stmt->bindValue(':days', $plan['duration_days'], PDO::PARAM_INT);
$stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
$stmt->execute();

// get expiry
$stmt = $conn->prepare("
    SELECT premium_expires 
    FROM users 
    WHERE id = :id
");
$stmt->bindValue(':id', $user['id']);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

sendResponse(true, "Subscription activated", [
    "plan_id"         => $plan_id,
    "premium_expires" => $row['premium_expires']
]);
?>
