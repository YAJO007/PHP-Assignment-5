<?php

declare(strict_types=1);

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /login');
    exit;
}

$userId = $_SESSION['user_id'];
$availableCourses = getAvailableCourses($userId);
$enrolledCourses = getEnrolledCourses($userId);

// Get enrollment counts
$enrollmentCounts = [];
foreach (array_merge($availableCourses, $enrolledCourses) as $course) {
    $enrollmentCounts[$course['id']] = getEnrollmentCount($course['id']);
}

renderView('courses', [
    'availableCourses' => $availableCourses,
    'enrolledCourses' => $enrolledCourses,
    'enrollmentCounts' => $enrollmentCounts
]);

