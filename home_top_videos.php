<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /home/top-videos
// Method: GET

requireMethod('GET');

// Simple "top videos" logic (latest videos)
$stmt = $conn->prepare("
    SELECT id, title, thumbnail, video_url, category_id
    FROM videos
    ORDER BY id DESC
    LIMIT 10
");
$stmt->execute();

$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Top videos", [
    "videos" => $videos
]);
?>
