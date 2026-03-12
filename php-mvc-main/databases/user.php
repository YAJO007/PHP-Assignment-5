<?php
declare(strict_types=1);

/**
 * User Database Functions
 */

function authenticateUser(string $username, string $password): array|false
{
    $conn = getConnection();
    $sql = "SELECT id, username, password, email, fullname FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        return $user;
    }

    return false;
}

function getUserById(int $userId): array|false
{
    $conn = getConnection();
    $sql = "SELECT id, username, email, fullname FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    return $result->fetch_assoc();
}

