<?php
include('../config/db.php');
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $cnic = $_POST['cnic'];
  $password = $_POST['password'];

  $check = "SELECT * FROM Passenger WHERE email = '$email'";
  $result = $conn->query($check);

  if ($result->num_rows > 0) {
    $success = "Email already registered. Try login.";
  } else {
    $sql = "INSERT INTO Passenger (name, email, phone, cnic, password)
            VALUES ('$name', '$email', '$phone', '$cnic', '$password')";
    if ($conn->query($sql)) {
      header("Location: user_login.php?msg=registered");
      exit;
    } else {
      $success = "Registration failed. Try again.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Sign Up - Railway Management</title>
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
      <h3>👤 User Sign Up</h3>
      
      <?php if ($success): ?>
        <div class="alert alert-warning"><?= $success ?></div>
      <?php endif; ?>
      
      <form method="POST">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Enter your full name" required>
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="text" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
        </div>
        
        <div class="form-group">
          <label for="cnic">CNIC Number</label>
          <input type="text" name="cnic" id="cnic" class="form-control" placeholder="CNIC (without dashes)" required>
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Create a password" required>
        </div>
        
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>
      
      <p>Already registered? <a href="user_login.php">Login here</a></p>
    </div>
  </div>
</body>
</html>

