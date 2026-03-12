<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกวิชา</title>
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
                <h1 class="text-xl font-light text-white">📚 วิชาเรียน</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-white/70 text-sm"><?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></span>
                    <a href="/logout" class="text-white/70 hover:text-white text-sm">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </header>

        <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-6 py-8">
        <!-- Tabs -->
        <div class="flex space-x-2 mb-8">
            <button onclick="showTab('available')" id="btn-available" 
                class="tab-btn bg-white text-purple-700 px-6 py-2 rounded-lg font-medium">
                📖 วิชาที่สามารถลงทะเบียน (<?= count($data['availableCourses']) ?>)
            </button>
            <button onclick="showTab('enrolled')" id="btn-enrolled" 
                class="tab-btn text-white/70 hover:text-white px-6 py-2 rounded-lg font-medium">
                 วิชาที่ลงทะเบียนแล้ว (<?= count($data['enrolledCourses']) ?>)
            </button>
        </div>

        <!-- Available Courses -->
        <section id="available" class="tab-section">
            <?php if (count($data['availableCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['availableCourses'] as $course): ?>
                        <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="mb-4">
                                <p class="text-purple-600 font-medium text-sm"><?= htmlspecialchars($course['course_code']) ?></p>
                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h3>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-4 space-y-1">
                                <p>👨‍🏫 <?= htmlspecialchars($course['professor']) ?></p>
                                <p> <?= $course['credit'] ?> หน่วยกิต | 📅 ภาค <?= $course['semester'] ?></p>
                            </div>

                            <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($course['description']) ?></p>

                            <button onclick="enrollConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg transition">
                                ลงทะเบียน
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/95 backdrop-blur-sm rounded-xl p-12 text-center">
                    <p class="text-gray-600">ไม่มีวิชาที่สามารถลงทะเบียนได้</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Enrolled Courses -->
        <section id="enrolled" class="tab-section hidden">
            <?php if (count($data['enrolledCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['enrolledCourses'] as $course): ?>
                        <div class="bg-white/95 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="mb-4">
                                <p class="text-green-600 font-medium text-sm"><?= htmlspecialchars($course['course_code']) ?></p>
                                <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($course['course_name']) ?></h3>
                            </div>
                            
                            <div class="text-sm text-gray-600 mb-4 space-y-1">
                                <p>👨‍🏫 <?= htmlspecialchars($course['professor']) ?></p>
                                <p>� <?= $course['credit'] ?> หน่วยกิต | 📅 ภาค <?= $course['semester'] ?></p>
                                <p>✅ ลงทะเบียน: <?= date('d/m/Y', strtotime($course['enrolled_at'])) ?></p>
                            </div>

                            <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($course['description']) ?></p>

                            <button onclick="withdrawConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg transition">
                                ถอนวิชา
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white/95 backdrop-blur-sm rounded-xl p-12 text-center">
                    <p class="text-gray-600">คุณยังไม่ได้ลงทะเบียนวิชาใด</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h2 id="modalTitle" class="text-lg font-semibold text-gray-800 mb-3"></h2>
            <p id="modalMessage" class="text-gray-600 mb-6"></p>
            <div class="flex gap-3">
                <button onclick="submitAction()" id="confirmBtn" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg">
                    ยืนยัน
                </button>
                <button onclick="closeModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 rounded-lg">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <script>
        let action = null;
        let courseId = null;

        // แสดง/ซ่อน Tab
        function showTab(tabName) {
            // ซ่อนทั้งหมด
            document.querySelectorAll('.tab-section').forEach(el => {
                el.classList.add('hidden');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-purple-700');
                btn.classList.add('text-white/70');
            });

            // แสดงที่เลือก
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById('btn-' + tabName).classList.remove('text-white/70');
            document.getElementById('btn-' + tabName).classList.add('bg-white', 'text-purple-700');
        }

        // ยืนยันการลงทะเบียน
        function enrollConfirm(id, name) {
            action = 'enroll';
            courseId = id;
            document.getElementById('modalTitle').textContent = '✓ ยืนยันการลงทะเบียน';
            document.getElementById('modalMessage').textContent = 'ลงทะเบียนวิชา "' + name + '" ใช่หรือไม่?';
            document.getElementById('modal').classList.remove('hidden');
        }

        // ยืนยันการถอนวิชา
        function withdrawConfirm(id, name) {
            action = 'withdraw';
            courseId = id;
            document.getElementById('modalTitle').textContent = '⚠️ ยืนยันการถอนวิชา';
            document.getElementById('modalMessage').textContent = 'ถอนวิชา "' + name + '" ใช่หรือไม่?';
            document.getElementById('modal').classList.remove('hidden');
        }

        // ปิด Modal
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            action = null;
            courseId = null;
        }

        // ส่งข้อมูล
        function submitAction() {
            const url = action === 'enroll' ? '/enroll' : '/withdraw';
            
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ courseId: courseId })
            })
            .then(res => res.json())
            .then(data => {
                closeModal();
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
            const modal = document.getElementById('modal');
            if (e.target === modal) closeModal();
        }
    </script>
</body>
</html>
