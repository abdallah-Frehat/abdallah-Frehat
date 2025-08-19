<?php
session_start();
include 'db_config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']) ? true : false;

    if (empty($email) || empty($password)) {
        $error = "البريد الإلكتروني وكلمة المرور مطلوبان!";
    } else { 
        $stmt = $conn->prepare("SELECT user_id, user_name, email, password, full_name FROM users WHERE email = ?");
        if ($stmt === false) {
            die("تحضير الاستعلام فشل: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_fullname'] = $user['full_name'];
                $_SESSION['user_name'] = $user['user_name'];
                
                if ($remember_me) {
                    $cookie_value = base64_encode($user['email'] . ':' . $user['user_id']);
                    setcookie('remember_me', $cookie_value, time() + (86400 * 30), "/");
                }
                
                header("Location: home.php");
                exit();
            } else {
                $error = "كلمة المرور غير صحيحة!";
            }
        } else {
            $error = "البريد الإلكتروني غير مسجل!";
        }
        
        $stmt->close();
    }
    $conn->close();
}

// إذا كان هناك كوكي "تذكرني" ولم يكن المستخدم مسجل دخول
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $cookie_data = base64_decode($_COOKIE['remember_me']);
    list($email, $user_id) = explode(':', $cookie_data);
    
    $stmt = $conn->prepare("SELECT user_id, user_name, email, full_name FROM users WHERE user_id = ? AND email = ?");
    $stmt->bind_param("is", $user_id, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_fullname'] = $user['full_name'];
        $_SESSION['user_name'] = $user['user_name'];
        header("Location: home.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="css/login.css" />
  <title>Login</title>
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
  <div class="login-container">
    <!-- عرض رسائل الخطأ -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <!-- عرض رسالة النجاح إذا كان هناك توجيه من صفحة أخرى -->
    <?php if (isset($_GET['success']) && !empty($_GET['success'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <form class="login-form" action="login.php" method="POST">
      <h1>Login</h1>

      <div class="input-box">
        <input type="email" name="email" placeholder="Email" required 
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        <img src="css/img/email-regular-24.png" class="icon" alt="Email Icon" />
      </div>

      <div class="input-box">
        <input type="password" name="password" placeholder="Password" required />
        <img src="css/img/lock-alt-regular-24.png" class="icon" alt="Password Icon" />
      </div>

      <div class="options">
        <label class="remember-me">
          <input type="checkbox" name="remember_me" /> Remember Me
        </label>
        <a href="http://localhost/Sky_line/forgot_password.php" class="forgot-link">Forgot Password?</a>
      </div>
      
      <button type="submit" class="btn">Login</button>

      <p class="signup-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </form>

    <a class="X" href="home.php">X</a>
  </div>
</body>
</html>