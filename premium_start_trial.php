<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /premium/start-trial
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

$user_id = $user['id'];

// 7-day trial (change as needed)
$stmt = $conn->prepare("
    UPDATE users
    SET premium = 1,
        premium_expires = DATE_ADD(NOW(), INTERVAL 7 DAY)
    WHERE id = :id
");
$stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Optional: return new expiry date
$stmt = $conn->prepare("
    SELECT premium_expires
    FROM users
    WHERE id = :id
");
$stmt->bindValue(':id', $user_id);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

sendResponse(true, "Trial activated", [
    "user_id"          => $user_id,
    "premium_expires"  => $user['premium_expires']
]);
?>
