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
  <link href="styles.css" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width:600px;">
  <h3 class="text-center mb-4 text-white">🗑️ Delete Train</h3>
  <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
  <?php if ($message): ?>
    <div class="alert alert-info text-center"><?= $message ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label text-white">Train Name</label>
          <input type="text" name="train_name" class="form-control" placeholder="Train Name" required>
        </div>
        <button type="submit" class="btn btn-danger w-100">Delete Train</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
