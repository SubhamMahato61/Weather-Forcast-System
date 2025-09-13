<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Simple hardcoded credentials (you can replace with DB later)
  if ($username === "admin" && $password === "pass123") {
    $_SESSION['logged_in'] = true;
   header("Location: dashboard.php");

    exit;
  } else {
    $error = "Invalid credentials";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <style>
    body { font-family: Arial; background: #f0f4f8; text-align: center; padding: 50px; }
    form { background: white; padding: 20px; display: inline-block; border-radius: 8px; }
    input { padding: 10px; margin: 10px; width: 200px; }
    button { padding: 10px 20px; background: #00796b; color: white; border: none; }
  </style>
</head>
<body>
  <h2>🔐 Admin Login</h2>
  <form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  </form>
</body>
</html>
