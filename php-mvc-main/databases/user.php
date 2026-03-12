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

function registerUser(string $username, string $email, string $fullname, string $password): array
{
    $conn = getConnection();
    
    // ตรวจสอบ username ซ้ำ
    $usernameCheckSql = "SELECT id FROM users WHERE username = ?";
    $usernameStmt = $conn->prepare($usernameCheckSql);
    $usernameStmt->bind_param("s", $username);
    $usernameStmt->execute();
    $usernameResult = $usernameStmt->get_result();
    
    if ($usernameResult->num_rows > 0) {
        return ['success' => false, 'message' => 'Username นี้มีผู้ใช้งานแล้ว'];
    }
    
    // ตรวจสอบ email ซ้ำ
    $emailCheckSql = "SELECT id FROM users WHERE email = ?";
    $emailStmt = $conn->prepare($emailCheckSql);
    $emailStmt->bind_param("s", $email);
    $emailStmt->execute();
    $emailResult = $emailStmt->get_result();
    
    if ($emailResult->num_rows > 0) {
        return ['success' => false, 'message' => 'Email นี้มีผู้ใช้งานแล้ว'];
    }
    
    // แฮช password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // บันทึกผู้ใช้ใหม่
    $sql = "INSERT INTO users (username, email, fullname, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $fullname, $hashedPassword);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ'];
    } else {
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $stmt->error];
    }
}


