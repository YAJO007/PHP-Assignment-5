<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ระบบลงทะเบียนเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-purple-700 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">🔐 เข้าสู่ระบบ</h1>

        <?php if (isset($data['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?= htmlspecialchars($data['error']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required autofocus 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-300 transform hover:scale-105 shadow-lg">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mt-8 text-sm">
            <p class="font-semibold text-blue-900 mb-2">📝 ตัวอย่างการใช้งาน:</p>
            <p class="text-blue-800">Username: <span class="font-mono bg-white px-2 py-1 rounded">student01</span></p>
            <p class="text-blue-800">Password: <span class="font-mono bg-white px-2 py-1 rounded">1234</span></p>
        </div>

        <div class="text-center mt-6">
            <a href="/" class="text-blue-600 hover:text-blue-800 font-medium transition">
                ← กลับไปหน้าแรก
            </a>
        </div>
    </div>
</body>
</html>
