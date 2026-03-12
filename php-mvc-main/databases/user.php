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
    
    // ตรวจสอบว่า username มีอยู่แล้วหรือไม่
    $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    
    if ($checkStmt->get_result()->num_rows > 0) {
        return ['success' => false, 'message' => 'Username หรือ Email นี้มีการใช้งานแล้ว'];
    }
    
    // แฮช password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // บันทึกผู้ใช้ใหม่
    $sql = "INSERT INTO users (username, email, fullname, password, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $email, $fullname, $hashedPassword);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'ลงทะเบียนสำเร็จ! กรุณาเข้าสู่ระบบ'];
    } else {
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลงทะเบียน'];
    }
}
