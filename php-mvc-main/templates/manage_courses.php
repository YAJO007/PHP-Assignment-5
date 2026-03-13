<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการรายวิชา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap');
        body { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-700 to-purple-900 min-h-screen">
    <!-- Header -->
    <header class="bg-white/5 backdrop-blur-sm border-b border-white/10">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-light text-white">📚 จัดการรายวิชา</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-white/70 text-sm"><?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></span>
                    <a href="/courses" class="text-white/70 hover:text-white text-sm">กลับหน้าวิชา</a>
                    <a href="/logout" class="text-white/70 hover:text-white text-sm">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-8">
        <!-- Header with Add Course Button -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-light text-white">รายวิชาทั้งหมด (<?= count($data['allCourses']) ?>)</h2>
            <div class="flex gap-4">
                <a href="/add_course" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    ➕ เพิ่มรายวิชา
                </a>
                <a href="/courses" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    กลับหน้าวิชา
                </a>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($data['allCourses'] as $course): ?>
                <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <div class="mb-4">
                        <p class="text-purple-600 font-medium text-sm"><?= htmlspecialchars($course['course_code']) ?></p>
                        <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h3>
                    </div>
                    
                    <div class="text-sm text-gray-600 mb-4 space-y-1">
                        <p>👨‍🏫 <?= htmlspecialchars($course['professor']) ?></p>
                        <p><?= $course['credit'] ?> หน่วยกิต | 📅 ภาค <?= $course['semester'] ?></p>
                        <p>👥 จำนวนนักศึกษาสูงสุด: <?= $course['max_students'] ?> คน</p>
                    </div>

                    <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($course['description']) ?></p>

                    <!-- Enrollment Count -->
                    <?php 
                    $enrollmentCount = getEnrollmentCount($course['id']);
                    $canDelete = $enrollmentCount === 0;
                    ?>
                    <div class="text-sm text-gray-600 mb-4">
                        <p>📝 จำนวนผู้ลงทะเบียน: <?= $enrollmentCount ?> คน</p>
                        <?php if (!$canDelete): ?>
                            <p class="text-red-500 text-xs">⚠️ ไม่สามารถลบได้เนื่องจากมีผู้ลงทะเบียนแล้ว</p>
                        <?php endif; ?>
                    </div>

                    <div class="flex gap-2">
                        <button onclick="editCourse(<?= $course['id'] ?>)" 
                            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg transition"
                            title="แก้ไขรายวิชา">
                            ✏️ แก้ไข
                        </button>
                        <button onclick="deleteConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>', <?= $canDelete ? 'true' : 'false' ?>)" 
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition <?= !$canDelete ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            title="ลบรายวิชา"
                            <?= !$canDelete ? 'disabled' : '' ?>>
                            🗑️
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($data['allCourses']) === 0): ?>
            <div class="bg-white/95 backdrop-blur-sm rounded-xl p-12 text-center">
                <p class="text-gray-600 mb-4">ยังไม่มีรายวิชาในระบบ</p>
                <a href="/add_course" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition">
                    ➕ เพิ่มรายวิชาแรก
                </a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h2 id="deleteModalTitle" class="text-lg font-semibold text-gray-800 mb-3">🗑️ ยืนยันการลบรายวิชา</h2>
            <p id="deleteModalMessage" class="text-gray-600 mb-6"></p>
            <div class="flex gap-3">
                <button onclick="submitDelete()" id="deleteConfirmBtn" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg">
                    ยืนยัน
                </button>
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteCourseId = null;

        function editCourse(courseId) {
            // TODO: Implement edit functionality
            alert('ฟังก์ชันแก้ไขรายวิชาจะเพิ่มในภายหลัง');
        }

        function deleteConfirm(courseId, courseName, canDelete) {
            if (!canDelete) {
                alert('ไม่สามารถลบรายวิชาที่มีผู้ลงทะเบียนแล้วได้');
                return;
            }
            
            deleteCourseId = courseId;
            document.getElementById('deleteModalTitle').textContent = '🗑️ ยืนยันการลบรายวิชา';
            document.getElementById('deleteModalMessage').textContent = 'ลบรายวิชา "' + courseName + '" ใช่หรือไม่?';
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteCourseId = null;
        }

        function submitDelete() {
            if (!deleteCourseId) return;
            
            fetch('/delete_course', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ courseId: deleteCourseId })
            })
            .then(res => res.json())
            .then(data => {
                closeDeleteModal();
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(err => alert('เกิดข้อผิดพลาด: ' + err.message));
        }

        // ปิด Modal เมื่อคลิกนอก
        window.onclick = function(e) {
            const modal = document.getElementById('deleteModal');
            if (e.target === modal) closeDeleteModal();
        }
    </script>
</body>
</html>
