<?php

declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'ไม่ได้เข้าสู่ระบบ']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $_SESSION['user_id'];
$courseId = $data['courseId'] ?? null;

if (!$courseId) {
    echo json_encode(['success' => false, 'message' => 'หมายเลขวิชาไม่ถูกต้อง']);
    exit;
}

if (withdrawCourse($userId, (int) $courseId)) {
    echo json_encode(['success' => true, 'message' => 'ถอนวิชาสำเร็จ']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการถอนวิชา']);
}
exit;

