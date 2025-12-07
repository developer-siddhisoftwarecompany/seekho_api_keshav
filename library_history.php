<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /library/history
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);  // Authenticated user array

$user_id = $user['id'];

// Fetch watch history
$stmt = $conn->prepare("
    SELECT h.id, h.video_id, h.created_at,
           v.title, v.thumbnail, v.video_url
    FROM history h
    INNER JOIN videos v ON v.id = h.video_id
    WHERE h.user_id = :uid
    ORDER BY h.created_at DESC
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->execute();

$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Watch history", [
    "user_id" => $user_id,
    "history" => $history
]);
?>
