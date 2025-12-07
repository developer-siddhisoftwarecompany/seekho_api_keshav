<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /library/saved
// Method: GET

requireMethod('GET');

$user = requireAuth($conn);

$user_id = $user['id'];

// Fetch saved videos
$stmt = $conn->prepare("
    SELECT sv.id, sv.video_id, sv.created_at,
           v.title, v.thumbnail, v.video_url, v.category_id
    FROM saved_videos sv
    INNER JOIN videos v ON v.id = sv.video_id
    WHERE sv.user_id = :uid
    ORDER BY sv.created_at DESC
");
$stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
$stmt->execute();

$saved = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Saved videos", [
    "user_id" => $user_id,
    "saved"   => $saved
]);
?>
