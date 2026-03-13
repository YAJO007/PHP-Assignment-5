<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มรายวิชา</title>
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
                <h1 class="text-xl font-light text-white">📚 เพิ่มรายวิชาใหม่</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-white/70 text-sm"><?= htmlspecialchars($_SESSION['fullname'] ?? 'ผู้ใช้') ?></span>
                    <a href="/courses" class="text-white/70 hover:text-white text-sm">กลับหน้าวิชา</a>
                    <a href="/logout" class="text-white/70 hover:text-white text-sm">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <?php if (isset($data['error'])): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
                    <?= htmlspecialchars($data['error']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($data['success'])): ?>
                <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg mb-6 text-sm">
                    <?= htmlspecialchars($data['success']) ?>
                </div>
                <div class="text-center">
                    <a href="/courses" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        กลับหน้าวิชา
                    </a>
                </div>
            <?php else: ?>
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">รหัสวิชา *</label>
                            <input type="text" id="course_code" name="course_code" required autofocus 
                                class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                                placeholder="เช่น CS101">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อวิชา *</label>
                            <input type="text" id="course_name" name="course_name" required 
                                class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                                placeholder="เช่น หลักการโปรแกรมมิ่ง">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">หน่วยกิต *</label>
                            <input type="number" id="credit" name="credit" required min="1" max="10" value="3"
                                class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ภาคการศึกษา *</label>
                            <select id="semester" name="semester" required 
                                class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                                <option value="">เลือกภาค</option>
                                <option value="1">ภาค 1</option>
                                <option value="2">ภาค 2</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">จำนวนนักศึกษาสูงสุด *</label>
                            <input type="number" id="max_students" name="max_students" required min="1" max="200" value="50"
                                class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">อาจารย์ผู้สอน *</label>
                        <input type="text" id="professor" name="professor" required 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="เช่น ผู้ช่วยศาสตราจารย์ สมชาย ใจดี">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">คำอธิบายวิชา</label>
                        <textarea id="description" name="description" rows="4" 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="รายละเอียดเกี่ยวกับวิชานี้..."></textarea>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 rounded-lg transition">
                            📚 เพิ่มรายวิชา
                        </button>
                        <a href="/courses" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 rounded-lg text-center transition">
                            ยกเลิก
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
