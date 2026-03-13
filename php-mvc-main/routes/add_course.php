<?php

declare(strict_types=1);

$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_code = trim($_POST['course_code'] ?? '');
    $course_name = trim($_POST['course_name'] ?? '');
    $credit = intval($_POST['credit'] ?? 0);
    $semester = intval($_POST['semester'] ?? 0);
    $max_students = intval($_POST['max_students'] ?? 0);
    $professor = trim($_POST['professor'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($course_code) || empty($course_name) || empty($professor)) {
        $data['error'] = 'กรุณากรอกข้อมูลให้ครบถ้วน (รหัสวิชา, ชื่อวิชา, อาจารย์ผู้สอน)';
    } elseif ($credit < 1 || $credit > 10) {
        $data['error'] = 'หน่วยกิตต้องอยู่ระหว่าง 1-10';
    } elseif ($semester < 1 || $semester > 2) {
        $data['error'] = 'ภาคการศึกษาต้องอยู่ระหว่าง 1-2';
    } elseif ($max_students < 1 || $max_students > 200) {
        $data['error'] = 'จำนวนนักศึกษาสูงสุดต้องอยู่ระหว่าง 1-200';
    } else {

        $result = addCourse($course_code, $course_name, $credit, $semester, $max_students, $professor, $description);
        
        if ($result['success']) {
            $data['success'] = $result['message'];
        } else {
            $data['error'] = $result['message'];
        }
    }
}

renderView('add_course', $data);
