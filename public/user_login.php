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
  <title>User Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="max-width: 500px;">
    <h3 class="text-center mb-4 text-white">🔐 User Login</h3>
    <?php if ($error): ?>
      <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button type="submit" class="btn btn-success w-100">Login</button>
      <p class="mt-3 text-center text-white">New user? <a href="user_signup.php" class="text-white">Sign up here</a></p>
    </form>
  </div>
</body>
</html>
