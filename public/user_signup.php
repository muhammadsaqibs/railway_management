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
  <title>User Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center mb-4">👤 User Sign Up</h3>
    <?php if ($success): ?>
      <div class="alert alert-warning text-center"><?= $success ?></div>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="name" class="form-control mb-3" placeholder="Full Name" required>
      <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
      <input type="text" name="phone" class="form-control mb-3" placeholder="Phone" required>
      <input type="text" name="cnic" class="form-control mb-3" placeholder="CNIC (without dashes)" required>
      <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
      <button type="submit" class="btn btn-primary w-100">Register</button>
      <p class="mt-3 text-center">Already registered? <a href="user_login.php">Login here</a></p>
    </form>
  </div>
</body>
</html>
