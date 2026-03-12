<?php

declare(strict_types=1);

$data = [];

// ถ้ามีการส่งข้อมูลแบบ POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // ตรวจสอบข้อมูล
    if (empty($username) || empty($email) || empty($fullname) || empty($password)) {
        $data['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
    } elseif (strlen($username) < 3) {
        $data['error'] = 'Username ต้องมีอย่างน้อย 3 ตัวอักษร';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $data['error'] = 'Email ไม่ถูกต้อง';
    } elseif (strlen($password) < 4) {
        $data['error'] = 'รหัสผ่านต้องมีอย่างน้อย 4 ตัวอักษร';
    } elseif ($password !== $confirm_password) {
        $data['error'] = 'รหัสผ่านไม่ตรงกัน';
    } else {
        // สมัครสมาชิก
        $result = registerUser($username, $email, $fullname, $password);
        
        if ($result['success']) {
            $data['success'] = $result['message'];
        } else {
            $data['error'] = $result['message'];
        }
    }
}

renderView('register', $data);
