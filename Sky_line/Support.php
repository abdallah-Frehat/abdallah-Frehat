<?php
session_start();
include 'db_config.php';

$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $message = trim($_POST['message']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Basic validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        $error_message = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Insert into database (you'll need to create a 'contact_messages' table)
        $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, first_name, last_name, email, mobile, message, created_at) 
                              VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("isssss", $user_id, $firstName, $lastName, $email, $mobile, $message);
            if ($stmt->execute()) {
                $success_message = "Thank you for your message! We'll get back to you soon.";
                // Clear form fields
                $firstName = $lastName = $email = $mobile = $message = '';
            } else {
                $error_message = "Error submitting your message. Please try again later.";
            }
            $stmt->close();
        } else {
            $error_message = "Database error. Please try again later.";
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/Support.css" />
    <link href="https://fonts.googleapis.com/css2?family=Lemonada:wght@400;600&display=swap" rel="stylesheet">
    <style>
      .alert {
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        text-align: center;
        font-weight: bold;
      }
      .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
      }
      .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <form method="POST" action="Support.php">
        <h1>Contact Us Form</h1>
        
        <?php if (!empty($success_message)): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
          <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <input type="text" id="firstName" name="firstName" placeholder="First Name" required 
               value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>" />
               
        <input type="text" id="lastName" name="lastName" placeholder="Last Name" required 
               value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>" />
               
        <input type="email" id="email" name="email" placeholder="Email" required 
               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" />
               
        <input type="text" id="mobile" name="mobile" placeholder="Mobile" 
               value="<?php echo isset($mobile) ? htmlspecialchars($mobile) : ''; ?>" />
               
        <h4>Type Your Message Here...</h4>
        <textarea name="message" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
        
        <input type="submit" value="Send" id="button" />
        
        <a href="home.php">x</a>
      </form>
    </div>
  </body>
</html>