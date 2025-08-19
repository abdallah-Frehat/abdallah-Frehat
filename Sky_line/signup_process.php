<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // تأكيد أن كلمات المرور متطابقة
    if ($password !== $confirm_password) {
        die("كلمة المرور وتأكيدها غير متطابقين!");
    }

    // تشفير كلمة المرور
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // تنفيذ الإدخال في قاعدة البيانات
    $sql = "INSERT INTO students (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "تم التسجيل بنجاح!";
    } else {
        echo "حدث خطأ: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
