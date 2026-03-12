<?php
declare(strict_types=1);

function getConnection(): mysqli
{
    $hostname = 'localhost';
    $dbName = 'enrollment';
    $username = 'demo123';
    $password = '123';
    $conn = new mysqli($hostname, $username, $password, $dbName);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Load database modules
require_once __DIR__ . '/../databases/user.php';
require_once __DIR__ . '/../databases/course.php';