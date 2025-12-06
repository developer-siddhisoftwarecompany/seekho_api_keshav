<?php

$host = "mysql-2033ca9-seekhoapi.c.aivencloud.com";
$port = 23785;
$dbname = "defaultdb";
$username = "avnadmin";
$password = getenv('DB_PASS'); // from Render env

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => '/etc/ssl/certs/ca-certificates.crt'
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
