<?php

declare(strict_types=1);

$data = [];

// ดึงข้อมูลรายวิชาทั้งหมด
$data['allCourses'] = getAllCourses();

renderView('manage_courses', $data);
