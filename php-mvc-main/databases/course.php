<?php
declare(strict_types=1);

/**
 * Course Database Functions
 */

function getAllCourses(): array
{
    $conn = getConnection();
    $sql = "SELECT * FROM courses ORDER BY semester, course_code";
    $result = $conn->query($sql);
    $courses = [];

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    return $courses;
}

function getAvailableCourses(int $userId): array
{
    $conn = getConnection();
    $sql = "SELECT c.* FROM courses c 
            WHERE c.id NOT IN (
                SELECT course_id FROM enrollments WHERE user_id = ?
            )
            ORDER BY c.semester, c.course_code";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = [];

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    return $courses;
}

function getEnrolledCourses(int $userId): array
{
    $conn = getConnection();
    $sql = "SELECT c.*, e.enrolled_at FROM courses c 
            INNER JOIN enrollments e ON c.id = e.course_id 
            WHERE e.user_id = ? 
            ORDER BY c.semester, c.course_code";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $courses = [];

    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }

    return $courses;
}

function getCourseById(int $courseId): array|false
{
    $conn = getConnection();
    $sql = "SELECT * FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false;
    }

    return $result->fetch_assoc();
}

function getEnrollmentCount(int $courseId): int
{
    $conn = getConnection();
    $sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return (int) $row['count'];
}

function isEnrolled(int $userId, int $courseId): bool
{
    $conn = getConnection();
    $sql = "SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $courseId);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

function enrollCourse(int $userId, int $courseId): bool
{
    // ตรวจสอบว่าลงทะเบียนแล้วหรือไม่
    if (isEnrolled($userId, $courseId)) {
        return false;
    }

    $conn = getConnection();
    $sql = "INSERT INTO enrollments (user_id, course_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $courseId);

    return $stmt->execute();
}

function withdrawCourse(int $userId, int $courseId): bool
{
    $conn = getConnection();
    $sql = "DELETE FROM enrollments WHERE user_id = ? AND course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $courseId);

    return $stmt->execute();
}

function addCourse(string $courseCode, string $courseName, int $credit, int $semester, int $maxStudents, string $professor, string $description = ''): array
{
    $conn = getConnection();
    
    // ตรวจสอบรหัสวิชาซ้ำ
    $courseCodeCheckSql = "SELECT id FROM courses WHERE course_code = ?";
    $courseCodeStmt = $conn->prepare($courseCodeCheckSql);
    $courseCodeStmt->bind_param("s", $courseCode);
    $courseCodeStmt->execute();
    $courseCodeResult = $courseCodeStmt->get_result();
    
    if ($courseCodeResult->num_rows > 0) {
        return ['success' => false, 'message' => 'รหัสวิชานี้มีอยู่แล้ว'];
    }
    
    // บันทึกรายวิชาใหม่
    $sql = "INSERT INTO courses (course_code, course_name, credit, semester, max_students, professor, description, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiss", $courseCode, $courseName, $credit, $semester, $maxStudents, $professor, $description);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'เพิ่มรายวิชาสำเร็จ!'];
    } else {
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $stmt->error];
    }
}

function deleteCourse(int $courseId): array
{
    $conn = getConnection();
    
    // ตรวจสอบว่ามีวิชานี้อยู่จริงหรือไม่
    $checkSql = "SELECT id FROM courses WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $courseId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        return ['success' => false, 'message' => 'ไม่พบรายวิชานี้'];
    }
    
    // ตรวจสอบว่ามีนักเรียนลงทะเบียนหรือไม่
    $enrollmentCheckSql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = ?";
    $enrollmentStmt = $conn->prepare($enrollmentCheckSql);
    $enrollmentStmt->bind_param("i", $courseId);
    $enrollmentStmt->execute();
    $enrollmentResult = $enrollmentStmt->get_result();
    $enrollmentRow = $enrollmentResult->fetch_assoc();
    
    if ($enrollmentRow['count'] > 0) {
        return ['success' => false, 'message' => 'ไม่สามารถลบรายวิชาที่มีนักเรียนลงทะเบียนแล้วได้'];
    }
    
    // ลบรายวิชา
    $sql = "DELETE FROM courses WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    
    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'ลบรายวิชาสำเร็จ!'];
    } else {
        return ['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $stmt->error];
    }
}
