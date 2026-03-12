<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกวิชา - ระบบลงทะเบียนเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-purple-800 min-h-screen p-4 sm:p-6">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <header class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">🎓 ระบบลงทะเบียนเรียน</h1>
                    <p class="text-gray-600">เลือกและจัดการวิชาเรียน</p>
                </div>
                <div class="text-center sm:text-right">
                    <p class="font-semibold text-gray-800 mb-2">👤 <?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></p>
                    <a href="/logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition">
                        ออกจากระบบ
                    </a>
                </div>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex gap-2 mb-6 flex-wrap">
            <button onclick="showTab('available')" id="btn-available" 
                class="tab-btn active bg-white text-purple-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition shadow-purple-500/50">
                📋 วิชาที่สามารถลงทะเบียน (<?= count($data['availableCourses']) ?>)
            </button>
            <button onclick="showTab('enrolled')" id="btn-enrolled" 
                class="tab-btn inactive bg-gray-100 text-gray-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition">
                ✓ วิชาที่ลงทะเบียนแล้ว (<?= count($data['enrolledCourses']) ?>)
            </button>
        </div>

        <!-- Available Courses Section -->
        <section id="available" class="tab-section active">
            <?php if (count($data['availableCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['availableCourses'] as $course): ?>
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition hover:-translate-y-1 p-6 border-l-4 border-purple-500 shadow-purple-500/30">
                            <p class="text-purple-600 font-bold text-sm mb-1"><?= htmlspecialchars($course['course_code']) ?></p>
                            <h3 class="text-lg font-bold text-gray-800 mb-3"><?= htmlspecialchars($course['course_name']) ?></h3>
                            
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></p>
                                <p><strong>หน่วยกิต:</strong> <?= $course['credit'] ?> | <strong>ภาค:</strong> <?= $course['semester'] ?></p>
                                <p class="text-xs">👥 ลงทะเบียน: <strong><?= $data['enrollmentCounts'][$course['id']] ?>/<?= $course['max_students'] ?></strong> คน</p>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>

                            <button onclick="enrollConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg transition shadow-purple-500/50">
                                ลงทะเบียน
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center text-gray-600">
                    <p class="text-lg">ไม่มีวิชาที่สามารถลงทะเบียนได้</p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Enrolled Courses Section -->
        <section id="enrolled" class="tab-section hidden">
            <?php if (count($data['enrolledCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['enrolledCourses'] as $course): ?>
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition hover:-translate-y-1 p-6 border-l-4 border-purple-400 shadow-purple-400/30">
                            <p class="text-purple-500 font-bold text-sm mb-1"><?= htmlspecialchars($course['course_code']) ?></p>
                            <h3 class="text-lg font-bold text-gray-800 mb-3"><?= htmlspecialchars($course['course_name']) ?></h3>
                            
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></p>
                                <p><strong>หน่วยกิต:</strong> <?= $course['credit'] ?> | <strong>ภาค:</strong> <?= $course['semester'] ?></p>
                                <p class="text-xs text-purple-600">✓ ลงทะเบียน: <?= date('d/m/Y', strtotime($course['enrolled_at'])) ?></p>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>

                            <button onclick="withdrawConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                class="w-full bg-purple-400 hover:bg-purple-500 text-white font-bold py-2 rounded-lg transition shadow-purple-400/50">
                                ถอนวิชา
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center text-gray-600">
                    <p class="text-lg">คุณยังไม่ได้ลงทะเบียนวิชาใด</p>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <!-- Confirmation Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-2xl p-6 max-w-sm w-full border-2 border-purple-500 shadow-purple-500/50">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-3"></h2>
            <p id="modalMessage" class="text-gray-600 mb-6"></p>
            <div class="flex gap-3">
                <button onclick="submitAction()" id="confirmBtn" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg transition shadow-purple-500/50">
                    ยืนยัน
                </button>
                <button onclick="closeModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 rounded-lg transition">
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
                el.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-purple-600');
                btn.classList.add('inactive', 'bg-gray-100', 'text-gray-600');
            });

            // แสดงที่เลือก
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById(tabName).classList.add('active');
            document.getElementById('btn-' + tabName).classList.remove('inactive', 'bg-gray-100', 'text-gray-600');
            document.getElementById('btn-' + tabName).classList.add('active', 'bg-white', 'text-purple-600');
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
