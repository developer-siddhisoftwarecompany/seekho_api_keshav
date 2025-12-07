<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /video
// Method: GET

requireMethod('GET');

$video_id = isset($_GET['video_id']) ? intval($_GET['video_id']) : 0;

if ($video_id <= 0) {
    sendResponse(false, "video_id required", null, 400);
}

$stmt = $conn->prepare("
    SELECT v.id, v.title, v.thumbnail, v.video_url, 
           v.category_id,
           c.name AS category_name
    FROM videos v
    LEFT JOIN categories c ON c.id = v.category_id
    WHERE v.id = :vid
    LIMIT 1
");
$stmt->bindValue(':vid', $video_id, PDO::PARAM_INT);
$stmt->execute();

$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    sendResponse(false, "Video not found", null, 404);
}

sendResponse(true, "Video details", [
    "video" => $video
]);
?>
