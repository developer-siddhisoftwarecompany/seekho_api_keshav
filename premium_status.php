<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /premium/status
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);

$user_id = $user['id'];

// fetch user premium data
$stmt = $conn->prepare("
    SELECT premium, premium_expires 
    FROM users
    WHERE id = :uid
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$isPremium = false;
if ($row['premium'] == 1 && strtotime($row['premium_expires']) > time()) {
    $isPremium = true;
}

// optional: auto-expire premium
if (!$isPremium) {
    // disable premium if expired
    $stmt = $conn->prepare("
        UPDATE users 
        SET premium = 0 
        WHERE id = :uid
    ");
    $stmt->bindValue(':uid', $user_id);
    $stmt->execute();
}

sendResponse(true, "Premium status checked", [
    "premium"          => $isPremium,
    "premium_expires"  => $row['premium_expires']
]);
?>
