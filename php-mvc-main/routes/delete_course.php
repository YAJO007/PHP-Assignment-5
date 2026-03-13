<?php

declare(strict_types=1);

// รับข้อมูลจาก POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $courseId = intval($input['courseId'] ?? 0);
    
    if ($courseId <= 0) {
        echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
        exit;
    }
    
    // ลบรายวิชา
    $result = deleteCourse($courseId);
    
    echo json_encode($result);
} else {
    // ถ้าไม่ใช่ POST request ให้ redirect ไปหน้า courses
    header('Location: /courses');
    exit;
}
