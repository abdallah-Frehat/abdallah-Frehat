<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

// جلب معلومات المستخدم من قاعدة البيانات
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT user_name, email, full_name, phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>شركة الطيران</title>
  <link rel="stylesheet" href="css/Style.css">
  <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .user-info {
      background-color: #f8f9fa;
      padding: 10px;
      border-radius: 5px;
      margin: 10px 0;
      text-align: center;
    }
    .logout-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 4px;
      cursor: pointer;
      margin-right: 10px;
    }
    .logout-btn:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>

  <header class="header">
    <h1>Sky Line</h1>
    <nav class="navbar">
      <a href="home.php">Main</a>
      <a href="Trips.php">Trips</a>
      <a href="Support.php">Contact Us</a>
      <div class="user-section">
        <?php if (isset($_SESSION['user_fullname'])): ?>
          <span class="welcome-msg">Welcome,  <?php echo htmlspecialchars($_SESSION['user_fullname']); ?></span>
          <a href="logout.php" class="logout-button">Log out</a>
        <?php else: ?>
          <a href="login.php" class="login-button">Log in -Sign up</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <div class="user-info">
    <p>Welcome<?php echo htmlspecialchars($user['fullname']); ?> | Email Address : <?php echo htmlspecialchars($user['email']); ?></p>
  </div>

  <section class="about">
    <h2> About Us</h2>
    <p>
    At Sky Line, we provide a unique and comfortable flying experience for travelers around the world, offering high-quality services at competitive prices</p>
    </p>
  </section>

  <footer class="footer">
    <p></p>
    <p>All rights reserved to the airline company © 2025</p>
    <div class="social">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

</body>
</html>