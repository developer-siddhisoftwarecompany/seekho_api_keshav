<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /video/related/id
// Method: GET

requireMethod('GET');

$video_id = isset($_GET['video_id']) ? intval($_GET['video_id']) : 0;

if ($video_id <= 0) {
    sendResponse(false, "video_id required", null, 400);
}

// Fetch this video's category
$stmt = $conn->prepare("
    SELECT category_id 
    FROM videos 
    WHERE id = :vid
");
$stmt->bindValue(':vid', $video_id, PDO::PARAM_INT);
$stmt->execute();

$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    sendResponse(false, "Video not found", null, 404);
}

$category_id = $video['category_id'];

// Fetch related in same category
$stmt = $conn->prepare("
    SELECT id, title, thumbnail, video_url, category_id
    FROM videos
    WHERE category_id = :cid AND id <> :vid
    ORDER BY id DESC
    LIMIT 10
");
$stmt->bindValue(':cid', $category_id);
$stmt->bindValue(':vid',  $video_id);
$stmt->execute();

$related = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Related videos", [
    "video_id"     => $video_id,
    "category_id"  => $category_id,
    "related"      => $related
]);
?>
