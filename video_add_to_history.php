<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /video/add-history
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

$data = getJsonInput();
$video_id = intval($data['video_id'] ?? 0);

if ($video_id <= 0) {
    sendResponse(false, "video_id required", null, 400);
}

// Insert into history
$stmt = $conn->prepare("
    INSERT INTO history (user_id, video_id, created_at)
    VALUES (:uid, :vid, NOW())
");
$stmt->bindValue(':uid', $user['id'], PDO::PARAM_INT);
$stmt->bindValue(':vid', $video_id, PDO::PARAM_INT);
$stmt->execute();

sendResponse(true, "History updated", [
    "user_id"  => $user['id'],
    "video_id" => $video_id
]);
?>
