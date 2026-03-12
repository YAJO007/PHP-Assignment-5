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
