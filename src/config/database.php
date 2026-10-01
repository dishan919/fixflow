<?php

$host = getenv("DB_HOST");
$database = getenv("DB_NAME");
$username = getenv("DB_USER");
$password = getenv("DB_PASSWORD");

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die(
        "Database connection failed: "
        . $conn->connect_error
    );
}

$conn->set_charset("utf8mb4");
?>