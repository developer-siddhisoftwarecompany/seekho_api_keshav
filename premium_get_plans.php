<?php
require 'config.php';
require 'helpers.php';

// Endpoint: /premium/plans
// Method: GET

requireMethod('GET');

// Fetch plans from DB
$stmt = $conn->prepare("
    SELECT id, plan_name, price, duration_days
    FROM plans
    ORDER BY price ASC
");
$stmt->execute();

$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(true, "Plans fetched", [
    "plans" => $plans
]);
?>
