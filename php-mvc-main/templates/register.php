<?php
// Suppress undefined variable warnings in template
$error = $data['error'] ?? null;
$success = $data['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ระบบลงทะเบียนเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-purple-700 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-2">📝 สมัครสมาชิก</h1>
        <p class="text-center text-gray-600 mb-8">สร้างบัญชีใหม่เพื่อเข้าใช้งาน</p>

        <!-- ข้อความสำเร็จ -->
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                ✓ <?= htmlspecialchars($success) ?>
            </div>
            <div class="text-center mt-8">
                <a href="/login" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition">
                    เข้าสู่ระบบ →
                </a>
            </div>
        <?php else: ?>
            <!-- ข้อความผิดพลาด -->
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    ✗ <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Form สมัครสมาชิก -->
            <form method="POST" class="space-y-4">
                <!-- ชื่อ -->
                <div>
                    <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                    <input type="text" id="fullname" name="fullname" required autofocus 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>

                <!-- ชื่อผู้ใช้ -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้</label>
                    <input type="text" id="username" name="username" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>

                <!-- รหัสผ่าน -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน</label>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- ยืนยันรหัสผ่าน -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="confirm_password" name="confirm_password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <!-- ปุ่มสมัคร -->
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg mt-6">
                    สมัครสมาชิก
                </button>
            </form>

            <!-- ลิงก์ไปเข้าสู่ระบบ -->
            <div class="text-center mt-6">
                <p class="text-gray-600">มีบัญชีแล้ว? <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium transition">เข้าสู่ระบบ</a></p>
            </div>
        <?php endif; ?>

        <!-- ลิงก์กลับหน้าแรก -->
        <div class="text-center mt-4">
            <a href="/" class="text-gray-600 hover:text-gray-800 font-medium transition">
                ← กลับไปหน้าแรก
            </a>
        </div>
    </div>
</body>
</html>
