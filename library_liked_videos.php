<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /library/liked
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);

$user_id = $user['id'];

// Fetch liked videos
$stmt = $conn->prepare("
    SELECT lv.id, lv.video_id, lv.created_at,
           v.title, v.thumbnail, v.video_url, v.category_id
    FROM liked_videos lv
    INNER JOIN videos v ON v.id = lv.video_id
    WHERE lv.user_id = :uid
    ORDER BY lv.created_at DESC
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->execute();

$liked = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Liked videos", [
    "user_id" => $user_id,
    "liked"   => $liked
]);
?>
