<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /videos/trending
// Method: GET

requireMethod('GET');

// Trending = latest videos (you can customize)
$stmt = $conn->prepare("
    SELECT id, title, thumbnail, video_url, category_id
    FROM videos
    ORDER BY id DESC
    LIMIT 10
");
$stmt->execute();

$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Trending videos", [
    "videos" => $videos
]);
?>
