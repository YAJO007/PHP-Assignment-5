<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap');
        body { font-family: 'Kanit', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-700 to-purple-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="text-5xl mb-4">📚</div>
            <h1 class="text-2xl font-light text-white mb-2">สมัครสมาชิก</h1>
            <p class="text-white/70 text-sm">สร้างบัญชีใหม่เพื่อเข้าใช้งานระบบ</p>
        </div>

        <!-- Registration Form -->
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
                    <a href="/login" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        เข้าสู่ระบบ
                    </a>
                </div>
            <?php else: ?>
                <form method="POST" class="space-y-4">
                    <div>
                        <input type="text" id="username" name="username" required autofocus 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="รหัสนิสิต">
                    </div>

                    <div>
                        <input type="email" id="email" name="email" required 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="อีเมล">
                    </div>

                    <div>
                        <input type="text" id="fullname" name="fullname" required 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="ชื่อ-นามสกุล">
                    </div>

                    <div>
                        <input type="password" id="password" name="password" required 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="รหัสผ่าน (อย่างน้อย 4 ตัวอักษร)">
                    </div>

                    <div>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                            class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                            placeholder="ยืนยันรหัสผ่าน">
                    </div>

                    <button type="submit" 
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 rounded-lg transition">
                        สมัครสมาชิก
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <p class="text-sm text-gray-600">
                        มีบัญชีอยู่แล้ว? 
                        <a href="/login" class="text-purple-600 hover:text-purple-700 font-medium">เข้าสู่ระบบ</a>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // ตรวจสอบการยืนยันรหัสผ่าน
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('รหัสผ่านไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
                return false;
            }
        });
    </script>
</body>
</html>
