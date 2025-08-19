<?php
// إعدادات الاتصال بقاعدة البيانات
$host = "localhost";        // أو 127.0.0.1
$username = "root";         // اسم المستخدم
$password = "";             // كلمة المرور (اتركها فارغة إذا لم تعيّن كلمة مرور)
$dbname = "sky_line";       // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = mysqli_connect($host, $username, $password, $dbname);

// التحقق من الاتصال
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}

// ضبط الترميز لدعم اللغة العربية
mysqli_set_charset($conn, "utf8mb4");
?>
