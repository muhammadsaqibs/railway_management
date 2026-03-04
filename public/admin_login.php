<?php
session_start();
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username === 'msaqib' && $password === '123') {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    header("Location: admin_dashboard.php");
    exit;
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Railway Management</title>
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
      <h3>⚙️ Admin Login</h3>
      
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      
      <form method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</body>
</html>

