<?php
declare(strict_types=1);

require_once DATABASES_DIR . 'user.php';

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // ตรวจสอบว่าไม่ว่างเปล่า
    if (!$username || !$email || !$fullname || !$password || !$confirmPassword) {
        $error = 'กรุณากรอกข้อมูลให้ครบทั้งหมด';
    }
    // ตรวจสอบ email format
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'กรุณากรอก Email ที่ถูกต้อง';
    }
    // ตรวจสอบ password ตรงกัน
    elseif ($password !== $confirmPassword) {
        $error = 'รหัสผ่านไม่ตรงกัน';
    }
    // ตรวจสอบความยาวรหัสผ่าน
    elseif (strlen($password) < 4) {
        $error = 'รหัสผ่านต้องมี 4 ตัวอักษรขึ้นไป';
    }
    else {
        // ลงทะเบียน
        $result = registerUser($username, $email, $fullname, $password);
        
        if ($result['success']) {
            $success = $result['message'];
            // เคลียร์ข้อมูล form
            $username = '';
            $email = '';
            $fullname = '';
            $password = '';
            $confirmPassword = '';
        } else {
            $error = $result['message'];
        }
    }
}

renderView('register', ['error' => $error, 'success' => $success]);
