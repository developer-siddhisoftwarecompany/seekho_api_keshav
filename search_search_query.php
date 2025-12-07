<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /search?query=
// Method: GET

requireMethod('GET');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query === '') {
    sendResponse(false, "query is required", null, 400);
}

$search = '%' . $query . '%';

// Search in titles
$stmt = $conn->prepare("
    SELECT id, title, thumbnail, video_url, category_id
    FROM videos
    WHERE title LIKE :search
    ORDER BY id DESC
");
$stmt->bindValue(':search', $search, PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Search results", [
    "query" => $query,
    "results" => $results
]);
?>
