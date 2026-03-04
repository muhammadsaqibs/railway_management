<?php
session_start();
include('../config/db.php');
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM Passenger WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $user['passenger_id'];
    $_SESSION['user_name'] = $user['name'];
    header("Location: user_dashboard.php");
    exit;
  } else {
    $error = "Invalid email or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login - Railway Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-fluid">
      <span class="navbar-brand">Railway Management</span>
      <div>
        <a href="home.php" class="btn btn-outline-light btn-sm">Home</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="auth-card container-small">
      <h3>🔐 User Login</h3>
      
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <form method="POST">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      
      <p>New user? <a href="user_signup.php">Sign up here</a></p>
    </div>
  </div>
</body>
</html>

