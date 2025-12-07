<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /home/categories
// Method: GET

requireMethod('GET');

// Fetch all categories
$stmt = $conn->prepare("
    SELECT id, name 
    FROM categories
    ORDER BY id ASC
");
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Categories loaded", [
    "categories" => $categories
]);
?>
