<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        renderView('login', ['error' => 'ชื่อผู้ใช้และรหัสผ่านจำเป็นต้องกรอก']);
        exit;
    }

    $user = authenticateUser($username, $password);
    
    if ($user !== false) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['logged_in'] = true;

        header('Location: /courses');
        exit;
    } else {
        renderView('login', ['error' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
        exit;
    }
}

renderView('login');

