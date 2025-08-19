<?php
session_start();

// مسح جميع بيانات الجلسة
$_SESSION = array();

// إذا كان هناك كوكي للجلسة، قم بحذفه
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// تدمير الجلسة
session_destroy();

// حذف كوكي "تذكرني" إذا كان موجوداً
if (isset($_COOKIE['remember_me'])) {
    setcookie('remember_me', '', time() - 3600, "/");
}

// توجيه المستخدم إلى صفحة الرئيسية
header("Location: home.php");
exit();
?>