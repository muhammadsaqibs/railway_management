<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

include('../config/db.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $train_name = $_POST['train_name'];

  // Check if train exists
  $check = "SELECT * FROM Train WHERE train_name = '$train_name'";
  $result = $conn->query($check);

  if ($result->num_rows > 0) {
    $conn->query("DELETE FROM Train WHERE train_name = '$train_name'");
    $message = "✅ Train and related bookings deleted successfully.";
  } else {
    $message = "❌ Train not found.";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Delete Train</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width:600px;">
  <h3 class="text-center mb-4">🗑️ Delete Train</h3>
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
  <?php if ($message): ?>
    <div class="alert alert-info text-center"><?= $message ?></div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="train_name" class="form-control mb-3" placeholder="Train Name" required>
    <button type="submit" class="btn btn-danger w-100">Delete Train</button>
  </form>
</div>
</body>
</html>
