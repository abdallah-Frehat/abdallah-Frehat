<?php
session_start();
include 'db_config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username = explode('@', $email)[0]; // إنشاء اسم مستخدم من البريد الإلكتروني

    // التحقق من البيانات
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "جميع الحقول مطلوبة!";
    } elseif ($password !== $confirm_password) {
        $error = "كلمات المرور غير متطابقة!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "البريد الإلكتروني غير صالح!";
    } else {
        // التحقق من وجود البريد الإلكتروني أو اسم المستخدم
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? OR user_name = ?");
        if ($check_stmt === false) {
            die("تحضير الاستعلام فشل: " . $conn->error);
        }
        $check_stmt->bind_param("ss", $email, $username);
        $check_stmt->execute();
        $check_stmt->store_result();
        
        if ($check_stmt->num_rows > 0) {
            $error = "البريد الإلكتروني أو اسم المستخدم مسجل مسبقاً!";
        } else {
            // إدراج المستخدم الجديد
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $conn->prepare("INSERT INTO users (user_name, email, password, full_name, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            
            if ($insert_stmt === false) {
                die("تحضير استعلام الإدراج فشل: " . $conn->error);
            }
            
            $insert_stmt->bind_param("ssss", $username, $email, $hashed_password, $fullname);
            
            if ($insert_stmt->execute()) {
                $success = "تم التسجيل بنجاح! سيتم توجيهك لصفحة الدخول خلال 3 ثواني";
                $_SESSION['user_email'] = $email;
                $_SESSION['user_fullname'] = $fullname;
                $_SESSION['user_name'] = $username;
                header("refresh:3;url=login.php");
            } else {
                $error = "خطأ في التسجيل: " . $insert_stmt->error;
            }
            
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="css/signup.css" />
  <title>Sign Up</title>
  <style>
    .alert {
      padding: 10px;
      margin: 10px 0;
      border-radius: 4px;
      text-align: center;
    }
    .alert-error {
      background-color: #ffebee;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }
    .alert-success {
      background-color: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #a5d6a7;
    }
  </style>
</head>
<body>
  <div class="signup-container">
    <!-- عرض رسائل الخطأ أو النجاح -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form class="signup-form" action="signup.php" method="POST">
      <h1>Sign Up</h1>

      <div class="input-box">
        <input type="text" name="fullname" placeholder="Full Name" required 
               value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" />
        <img src="css/img/user-circle-regular-24.png" class="icon" alt="User Icon" />
      </div>

      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required 
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        <img src="css/img/email-regular-24.png" class="icon" alt="Email Icon" />
      </div>

      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required />
        <img src="css/img/lock-alt-regular-24.png" class="icon" alt="Password Icon" />
      </div>

      <div class="input-box">
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <img src="css/img/lock-alt-regular-24.png" class="icon" alt="Confirm Password Icon" />
      </div>

      <button type="submit" class="btn">Sign Up</button>

      <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
    </form>

    <a class="X" href="home.php">X</a>
  </div>
</body>
</html>