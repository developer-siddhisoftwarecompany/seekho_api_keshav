<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /category/category_videos
// Method: POST

requireMethod('POST');

$data = getJsonInput();
$category_id = intval($data['category_id'] ?? 0);

if ($category_id <= 0) {
    sendResponse(false, 'category_id required', null, 400);
}

// Fetch videos
$stmt = $conn->prepare("
    SELECT id, title, thumbnail, video_url 
    FROM videos 
    WHERE category_id = :cid
    ORDER BY id DESC
");
$stmt->bindValue(':cid', $category_id, PDO::PARAM_INT);
$stmt->execute();

$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, 'Category videos', [
    'category_id' => $category_id,
    'videos'      => $videos
]);
?>
