<?php
/**
 * Database Setup & Test Script
 * This script will help you set up the enrollment database
 */

try {
    // Connect to MySQL without database
    $conn = new mysqli('localhost', 'root', '');
    
    if ($conn->connect_error) {
        echo "<div style='color: red; font-size: 16px; padding: 20px;'>";
        echo "<strong>❌ MySQL Connection Failed:</strong><br>";
        echo $conn->connect_error;
        echo "</div>";
        exit;
    }
    
    echo "<h1>📊 Database Setup</h1>";
    
    // Create database
    $createDb = "CREATE DATABASE IF NOT EXISTS enrollment";
    if (!$conn->query($createDb)) {
        echo "<div style='color: red;'><strong>Error creating database:</strong> " . $conn->error . "</div>";
        exit;
    }
    echo "<div style='color: green;'>✓ Database 'enrollment' created/exists</div>";
    
    // Select database
    $conn->select_db('enrollment');
    
    // Create users table
    $createUsers = "
    CREATE TABLE IF NOT EXISTS users (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100),
        fullname VARCHAR(200),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    ";
    if (!$conn->query($createUsers)) {
        echo "<div style='color: red;'><strong>Error creating users table:</strong> " . $conn->error . "</div>";
    } else {
        echo "<div style='color: green;'>✓ Table 'users' created/exists</div>";
    }
    
    // Create courses table
    $createCourses = "
    CREATE TABLE IF NOT EXISTS courses (
        id INT PRIMARY KEY AUTO_INCREMENT,
        course_code VARCHAR(50) UNIQUE NOT NULL,
        course_name VARCHAR(200) NOT NULL,
        credit INT DEFAULT 3,
        semester INT,
        max_students INT DEFAULT 50,
        professor VARCHAR(100),
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
    ";
    if (!$conn->query($createCourses)) {
        echo "<div style='color: red;'><strong>Error creating courses table:</strong> " . $conn->error . "</div>";
    } else {
        echo "<div style='color: green;'>✓ Table 'courses' created/exists</div>";
    }
    
    // Create enrollments table
    $createEnrollments = "
    CREATE TABLE IF NOT EXISTS enrollments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_enrollment (user_id, course_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )
    ";
    if (!$conn->query($createEnrollments)) {
        echo "<div style='color: red;'><strong>Error creating enrollments table:</strong> " . $conn->error . "</div>";
    } else {
        echo "<div style='color: green;'>✓ Table 'enrollments' created/exists</div>";
    }
    
    // Check if data exists
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $row = $result->fetch_assoc();
    
    if ($row['count'] == 0) {
        echo "<h2>📝 Inserting Sample Data...</h2>";
        
        // Insert users
        $insertUsers = "
        INSERT INTO users (username, password, email, fullname) VALUES
        ('student01', '" . password_hash('1234', PASSWORD_BCRYPT) . "', 'student01@example.com', 'นักเรียน 1'),
        ('student02', '" . password_hash('1234', PASSWORD_BCRYPT) . "', 'student02@example.com', 'นักเรียน 2'),
        ('student03', '" . password_hash('1234', PASSWORD_BCRYPT) . "', 'student03@example.com', 'นักเรียน 3')
        ";
        
        if ($conn->query($insertUsers)) {
            echo "<div style='color: green;'>✓ Sample users inserted</div>";
        } else {
            echo "<div style='color: orange;'>⚠ Users might already exist: " . $conn->error . "</div>";
        }
        
        // Insert courses
        $insertCourses = "
        INSERT INTO courses (course_code, course_name, credit, semester, max_students, professor, description) VALUES
        ('CS101', 'หลักการโปรแกรมมิ่ง', 3, 1, 50, 'ผู้ช่วยศาสตราจารย์ เอ', 'เรียนรู้พื้นฐานของการเขียนโปรแกรม'),
        ('CS102', 'โครงสร้างข้อมูล', 3, 1, 40, 'ผู้ช่วยศาสตราจารย์ บี', 'เรียนรู้เกี่ยวกับโครงสร้างข้อมูลต่างๆ'),
        ('CS201', 'ฐานข้อมูล', 3, 2, 45, 'ผู้ช่วยศาสตราจารย์ ซี', 'เรียนรู้การออกแบบและจัดการฐานข้อมูล'),
        ('CS202', 'เว็บแอปพลิเคชัน', 3, 2, 50, 'ผู้ช่วยศาสตราจารย์ ดี', 'พัฒนาเว็บแอปพลิเคชันด้วย PHP'),
        ('CS301', 'ระบบปฏิบัติการ', 3, 3, 30, 'ผู้ช่วยศาสตราจารย์ อี', 'เรียนรู้หลักการของระบบปฏิบัติการ'),
        ('CS302', 'เครือข่าย', 3, 3, 35, 'ผู้ช่วยศาสตราจารย์ เอฟ', 'เรียนรู้เกี่ยวกับเครือข่ายคอมพิวเตอร์')
        ";
        
        if ($conn->query($insertCourses)) {
            echo "<div style='color: green;'>✓ Sample courses inserted</div>";
        } else {
            echo "<div style='color: orange;'>⚠ Courses might already exist: " . $conn->error . "</div>";
        }
    } else {
        echo "<div style='color: green;'>✓ Database already has data (" . $row['count'] . " users)</div>";
    }
    
    echo "<h2 style='color: green;'>✅ Setup Complete!</h2>";
    echo "<p><a href='/'>← Back to Home</a></p>";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<div style='color: red; font-size: 16px; padding: 20px;'>";
    echo "<strong>❌ Error:</strong><br>";
    echo $e->getMessage();
    echo "</div>";
}
?>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
        background: #f5f5f5;
    }
    div {
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
    }
    h1 { color: #333; }
    h2 { color: #333; margin-top: 30px; }
    a { color: #0066cc; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
