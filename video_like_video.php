<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /video/like
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

$data = getJsonInput();
$video_id = intval($data['video_id'] ?? 0);

if ($video_id <= 0) {
    sendResponse(false, "video_id required", null, 400);
}

$user_id = $user['id'];

// Check if already liked
$stmt = $conn->prepare("
    SELECT id 
    FROM liked_videos 
    WHERE user_id = :uid AND video_id = :vid
");
$stmt->bindValue(':uid', $user_id);
$stmt->bindValue(':vid', $video_id);
$stmt->execute();

if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendResponse(false, "Already liked", null, 409);
}

// Insert new like
$stmt = $conn->prepare("
    INSERT INTO liked_videos (user_id, video_id, created_at)
    VALUES (:uid, :vid, NOW())
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':vid', $video_id, PDO::PARAM_INT);
$stmt->execute();

sendResponse(true, "Video liked", [
    "user_id"  => $user_id,
    "video_id" => $video_id
]);
?>
