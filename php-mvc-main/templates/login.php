    <!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
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
            <h1 class="text-2xl font-light text-white mb-2">เข้าสู่ระบบ</h1>
        </div>

        <!-- Login Form -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <?php if (isset($data['error'])): ?>
                <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
                    <?= htmlspecialchars($data['error']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-5">
                <div>
                    <input type="text" id="username" name="username" required autofocus 
                        class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                        placeholder="ชื่อผู้ใช้">
                </div>

                <div>
                    <input type="password" id="password" name="password" required 
                        class="w-full px-4 py-3 border-0 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition"
                        placeholder="รหัสผ่าน">
                </div>

                <button type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-3 rounded-lg transition">
                    เข้าสู่ระบบ
                </button>
            </form>

            <!-- Back to Register -->
            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-sm text-gray-600 mb-3">
                    ยังไม่มีบัญชี? 
                    <a href="/register" class="text-purple-600 hover:text-purple-700 font-medium">สมัครสมาชิก</a>
                </p>
                
                <div class="border-t border-gray-200 pt-3">
                    <p class="text-xs text-gray-500 text-center mb-2">บัญชีสำหรับทดลอง</p>
                    <div class="text-center">
                        <span class="text-xs text-gray-600">demo / 1234</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
