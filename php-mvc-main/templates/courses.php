<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกวิชา - ระบบลงทะเบียนเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-purple-700 min-h-screen p-4 sm:p-6">
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
                class="tab-btn active bg-white text-blue-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition">
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
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition hover:-translate-y-1 p-6 border-l-4 border-blue-600">
                            <p class="text-blue-600 font-bold text-sm mb-1"><?= htmlspecialchars($course['course_code']) ?></p>
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
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">
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
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition hover:-translate-y-1 p-6 border-l-4 border-green-600">
                            <p class="text-green-600 font-bold text-sm mb-1"><?= htmlspecialchars($course['course_code']) ?></p>
                            <h3 class="text-lg font-bold text-gray-800 mb-3"><?= htmlspecialchars($course['course_name']) ?></h3>
                            
                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></p>
                                <p><strong>หน่วยกิต:</strong> <?= $course['credit'] ?> | <strong>ภาค:</strong> <?= $course['semester'] ?></p>
                                <p class="text-xs text-green-700">✓ ลงทะเบียน: <?= date('d/m/Y', strtotime($course['enrolled_at'])) ?></p>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 h-12 overflow-hidden">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>

                            <button onclick="withdrawConfirm(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-lg transition">
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
        <div class="bg-white rounded-lg shadow-2xl p-6 max-w-sm w-full">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-800 mb-3"></h2>
            <p id="modalMessage" class="text-gray-600 mb-6"></p>
            <div class="flex gap-3">
                <button onclick="submitAction()" id="confirmBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">
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
                btn.classList.remove('active', 'bg-white', 'text-blue-600');
                btn.classList.add('inactive', 'bg-gray-100', 'text-gray-600');
            });

            // แสดงที่เลือก
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById(tabName).classList.add('active');
            document.getElementById('btn-' + tabName).classList.remove('inactive', 'bg-gray-100', 'text-gray-600');
            document.getElementById('btn-' + tabName).classList.add('active', 'bg-white', 'text-blue-600');
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
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <header class="bg-white rounded-lg shadow-lg p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">🎓 ระบบลงทะเบียนเรียน</h1>
                <p class="text-gray-600 text-sm">เลือกและจัดการวิชาเรียนของคุณ</p>
            </div>
            <div class="text-right">
                <div class="font-semibold text-gray-800 mb-2">👤 <?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></div>
                <a href="/logout" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300 text-sm">
                    ออกจากระบบ
                </a>
            </div>
        </header>

        <!-- Tabs -->
        <div class="flex gap-3 mb-6 flex-wrap">
            <button class="tab-button active bg-white text-blue-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition" 
                onclick="switchTab('available-courses')">
                📋 วิชาที่สามารถลงทะเบียนได้ (<?= count($data['availableCourses']) ?>)
            </button>
            <button class="tab-button inactive bg-gray-100 text-gray-600 font-bold py-3 px-6 rounded-lg shadow-lg hover:shadow-xl transition" 
                onclick="switchTab('enrolled-courses')">
                ✓ วิชาที่ลงทะเบียนแล้ว (<?= count($data['enrolledCourses']) ?>)
            </button>
        </div>

        <!-- Available Courses Tab -->
        <div id="available-courses" class="tab-content active">
            <?php if (count($data['availableCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['availableCourses'] as $course): ?>
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden">
                            <div class="border-l-4 border-blue-600 p-6">
                                <p class="text-blue-600 font-bold text-sm mb-2"><?= htmlspecialchars($course['course_code']) ?></p>
                                <h3 class="text-xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($course['course_name']) ?></h3>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <p><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></p>
                                    <p><strong>หน่วยกิต:</strong> <?= $course['credit'] ?></p>
                                    <p><strong>ภาคการศึกษา:</strong> <?= $course['semester'] ?></p>
                                </div>

                                <div class="bg-gray-100 p-3 rounded mb-4 text-sm">
                                    ลงทะเบียนแล้ว: <strong><?= $data['enrollmentCounts'][$course['id']] ?>/<?= $course['max_students'] ?></strong> คน
                                </div>

                                <p class="text-gray-600 text-sm mb-4 min-h-10">
                                    <?= htmlspecialchars($course['description']) ?>
                                </p>

                                <button onclick="confirmEnroll(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                    ลงทะเบียน
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center text-gray-600">
                    <p class="text-lg">ไม่มีวิชาที่สามารถลงทะเบียนได้ หรือคุณได้ลงทะเบียนทั้งหมดแล้ว</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Enrolled Courses Tab -->
        <div id="enrolled-courses" class="tab-content hidden">
            <?php if (count($data['enrolledCourses']) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($data['enrolledCourses'] as $course): ?>
                        <div class="bg-white rounded-lg shadow-lg hover:shadow-2xl transition transform hover:scale-105 overflow-hidden">
                            <div class="border-l-4 border-green-600 p-6">
                                <p class="text-green-600 font-bold text-sm mb-2"><?= htmlspecialchars($course['course_code']) ?></p>
                                <h3 class="text-xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($course['course_name']) ?></h3>

                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <p><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></p>
                                    <p><strong>หน่วยกิต:</strong> <?= $course['credit'] ?></p>
                                    <p><strong>ภาคการศึกษา:</strong> <?= $course['semester'] ?></p>
                                </div>

                                <div class="bg-green-50 p-3 rounded mb-4 text-sm text-green-700">
                                    ✓ ลงทะเบียนตั้งแต่: <strong><?= date('d/m/Y H:i', strtotime($course['enrolled_at'])) ?></strong>
                                </div>

                                <p class="text-gray-600 text-sm mb-4 min-h-10">
                                    <?= htmlspecialchars($course['description']) ?>
                                </p>

                                <button onclick="confirmWithdraw(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                                    ถอนวิชา
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-12 text-center text-gray-600">
                    <p class="text-lg">คุณยังไม่ได้ลงทะเบียนวิชาใดๆ</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for confirmation -->
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-4">ยืนยันการลงทะเบียน</h2>
            <p id="modalMessage" class="text-gray-600 mb-8">คุณต้องการลงทะเบียนวิชา [ชื่อวิชา] ใช่หรือไม่?</p>
            <div class="flex gap-4">
                <button id="confirmBtn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300">
                    ยืนยัน
                </button>
                <button onclick="closeModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg transition duration-300">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
                tab.classList.remove('active');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'text-blue-600');
                btn.classList.add('inactive', 'bg-gray-100', 'text-gray-600');
            });

            // Show selected tab
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById(tabName).classList.add('active');

            // Activate clicked button
            event.target.classList.remove('inactive', 'bg-gray-100', 'text-gray-600');
            event.target.classList.add('active', 'bg-white', 'text-blue-600');
        }

        function confirmEnroll(courseId, courseName) {
            document.getElementById('modalTitle').textContent = '✓ ยืนยันการลงทะเบียน';
            document.getElementById('modalMessage').textContent = 'คุณต้องการลงทะเบียนวิชา "' + courseName + '" ใช่หรือไม่?';
            document.getElementById('confirmBtn').onclick = () => enrollCourse(courseId);
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function confirmWithdraw(courseId, courseName) {
            document.getElementById('modalTitle').textContent = '⚠️ ยืนยันการถอนวิชา';
            document.getElementById('modalMessage').textContent = 'คุณต้องการถอนวิชา "' + courseName + '" ใช่หรือไม่?';
            document.getElementById('confirmBtn').onclick = () => withdrawCourse(courseId);
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        function enrollCourse(courseId) {
            closeModal();
            fetch('/enroll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ courseId: courseId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาด: ' + error.message);
            });
        }

        function withdrawCourse(courseId) {
            closeModal();
            fetch('/withdraw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ courseId: courseId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาด: ' + error.message);
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('confirmModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-text h1 {
            color: #333;
            margin-bottom: 5px;
        }

        .header-text p {
            color: #666;
            font-size: 14px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .logout-btn {
            background: #ff6b6b;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-button {
            background: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .tab-button.active {
            background: #667eea;
            color: white;
        }

        .tab-button.inactive {
            background: white;
            color: #667eea;
        }

        .tab-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .course-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .course-header {
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 15px;
        }

        .course-code {
            color: #667eea;
            font-weight: bold;
            font-size: 14px;
        }

        .course-name {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin: 8px 0;
        }

        .course-info {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .course-info strong {
            color: #333;
        }

        .course-description {
            color: #666;
            font-size: 13px;
            line-height: 1.5;
            margin: 15px 0;
            min-height: 40px;
        }

        .enrollment-info {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 12px;
            color: #666;
        }

        .course-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-enroll {
            background: #667eea;
            color: white;
        }

        .btn-enroll:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-withdraw {
            background: #ff6b6b;
            color: white;
        }

        .btn-withdraw:hover {
            background: #ff5252;
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            color: #666;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .modal-content h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .modal-content p {
            color: #666;
            margin-bottom: 25px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .modal-btn-confirm {
            background: #667eea;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #5568d3;
        }

        .modal-btn-cancel {
            background: #ddd;
            color: #333;
        }

        .modal-btn-cancel:hover {
            background: #ccc;
        }

        .loading {
            display: none;
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .success-message {
            background: #51cf66;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error-message {
            background: #ff6b6b;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-text">
                <h1>🎓 ระบบลงทะเบียนเรียน</h1>
                <p>เลือกและจัดการวิชาเรียนของคุณ</p>
            </div>
            <div class="user-info">
                <div class="user-name">👤 <?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></div>
                <a href="/logout" class="logout-btn">ออกจากระบบ</a>
            </div>
        </header>

        <div class="tabs">
            <button class="tab-button active" onclick="switchTab('available-courses')">
                📋 วิชาที่สามารถลงทะเบียนได้ (<?= count($data['availableCourses']) ?>)
            </button>
            <button class="tab-button inactive" onclick="switchTab('enrolled-courses')">
                ✓ วิชาที่ลงทะเบียนแล้ว (<?= count($data['enrolledCourses']) ?>)
            </button>
        </div>

        <div id="available-courses" class="tab-content active">
            <?php if (count($data['availableCourses']) > 0): ?>
                <div class="course-grid">
                    <?php foreach ($data['availableCourses'] as $course): ?>
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-code"><?= htmlspecialchars($course['course_code']) ?></div>
                                <div class="course-name"><?= htmlspecialchars($course['course_name']) ?></div>
                            </div>

                            <div class="course-info">
                                <div><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></div>
                                <div><strong>หน่วยกิต:</strong> <?= $course['credit'] ?></div>
                                <div><strong>ภาคการศึกษา:</strong> <?= $course['semester'] ?></div>
                            </div>

                            <div class="enrollment-info">
                                ลงทะเบียนแล้ว: <strong><?= $data['enrollmentCounts'][$course['id']] ?>/<?= $course['max_students'] ?></strong> คน
                            </div>

                            <div class="course-description">
                                <?= htmlspecialchars($course['description']) ?>
                            </div>

                            <div class="course-buttons">
                                <button class="btn btn-enroll" onclick="confirmEnroll(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')">
                                    ลงทะเบียน
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <p>ไม่มีวิชาที่สามารถลงทะเบียนได้ หรือคุณได้ลงทะเบียนทั้งหมดแล้ว</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="enrolled-courses" class="tab-content">
            <?php if (count($data['enrolledCourses']) > 0): ?>
                <div class="course-grid">
                    <?php foreach ($data['enrolledCourses'] as $course): ?>
                        <div class="course-card">
                            <div class="course-header">
                                <div class="course-code"><?= htmlspecialchars($course['course_code']) ?></div>
                                <div class="course-name"><?= htmlspecialchars($course['course_name']) ?></div>
                            </div>

                            <div class="course-info">
                                <div><strong>อาจารย์:</strong> <?= htmlspecialchars($course['professor']) ?></div>
                                <div><strong>หน่วยกิต:</strong> <?= $course['credit'] ?></div>
                                <div><strong>ภาคการศึกษา:</strong> <?= $course['semester'] ?></div>
                            </div>

                            <div class="enrollment-info">
                                ลงทะเบียนตั้งแต่: <strong><?= date('d/m/Y H:i', strtotime($course['enrolled_at'])) ?></strong>
                            </div>

                            <div class="course-description">
                                <?= htmlspecialchars($course['description']) ?>
                            </div>

                            <div class="course-buttons">
                                <button class="btn btn-withdraw" onclick="confirmWithdraw(<?= $course['id'] ?>, '<?= htmlspecialchars($course['course_name']) ?>')">
                                    ถอนวิชา
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <p>คุณยังไม่ได้ลงทะเบียนวิชาใดๆ</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for confirmation -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle">ยืนยันการลงทะเบียน</h2>
            <p id="modalMessage">คุณต้องการลงทะเบียนวิชา [ชื่อวิชา] ใช่หรือไม่?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-confirm" id="confirmBtn">ยืนยัน</button>
                <button class="modal-btn modal-btn-cancel" onclick="closeModal()">ยกเลิก</button>
            </div>
        </div>
    </div>

    <script>
        let pendingAction = null;

        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('inactive');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active to clicked button
            event.target.classList.add('active');
            event.target.classList.remove('inactive');
        }

        function confirmEnroll(courseId, courseName) {
            document.getElementById('modalTitle').textContent = '✓ ยืนยันการลงทะเบียน';
            document.getElementById('modalMessage').textContent = 'คุณต้องการลงทะเบียนวิชา "' + courseName + '" ใช่หรือไม่?';
            document.getElementById('confirmBtn').onclick = () => enrollCourse(courseId);
            document.getElementById('confirmModal').classList.add('active');
        }

        function confirmWithdraw(courseId, courseName) {
            document.getElementById('modalTitle').textContent = '⚠️ ยืนยันการถอนวิชา';
            document.getElementById('modalMessage').textContent = 'คุณต้องการถอนวิชา "' + courseName + '" ใช่หรือไม่?';
            document.getElementById('confirmBtn').onclick = () => withdrawCourse(courseId);
            document.getElementById('confirmModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.remove('active');
        }

        function enrollCourse(courseId) {
            closeModal();
            fetch('/enroll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ courseId: courseId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาด: ' + error.message);
            });
        }

        function withdrawCourse(courseId) {
            closeModal();
            fetch('/withdraw', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ courseId: courseId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ ' + data.message);
                    location.reload();
                } else {
                    alert('✗ ' + data.message);
                }
            })
            .catch(error => {
                alert('เกิดข้อผิดพลาด: ' + error.message);
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('confirmModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
