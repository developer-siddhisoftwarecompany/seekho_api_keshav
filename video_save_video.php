<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /video/save
// Method: POST

requireMethod('POST');

$user = requireAuth($conn);

$data = getJsonInput();
$video_id = intval($data['video_id'] ?? 0);

if ($video_id <= 0) {
    sendResponse(false, "video_id required", null, 400);
}

$user_id = $user['id'];

// already saved?
$stmt = $conn->prepare("
    SELECT id 
    FROM saved_videos 
    WHERE user_id = :uid AND video_id = :vid
");
$stmt->bindValue(':uid', $user_id);
$stmt->bindValue(':vid', $video_id);
$stmt->execute();

if ($stmt->fetch(PDO::FETCH_ASSOC)) {
    sendResponse(false, "Already saved", null, 409);
}

// insert save record
$stmt = $conn->prepare("
    INSERT INTO saved_videos (user_id, video_id, created_at)
    VALUES (:uid, :vid, NOW())
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':vid', $video_id, PDO::PARAM_INT);
$stmt->execute();

sendResponse(true, "Video saved", [
    "user_id"  => $user_id,
    "video_id" => $video_id
]);
?>
