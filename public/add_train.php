<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

include('../config/db.php');
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['train_name'];
  $from = $_POST['from_station'];
  $to = $_POST['to_station'];
  $seats = $_POST['total_seats'];
  $time = $_POST['schedule_time'];

  $insert = "INSERT INTO Train (train_name, from_station, to_station, total_seats, available_seats, schedule_time)
             VALUES ('$name', '$from', '$to', '$seats', '$seats', '$time')";

  if ($conn->query($insert)) {
    $success = "✅ Train added successfully.";
  } else {
    $success = "❌ Error adding train: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Add Train</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width:600px;">
    <h3 class="text-center mb-4">➕ Add New Train</h3>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($success): ?>
      <div class="alert alert-info text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="train_name" class="form-control mb-3" placeholder="Train Name" required>
      <input type="text" name="from_station" class="form-control mb-3" placeholder="From Station" required>
      <input type="text" name="to_station" class="form-control mb-3" placeholder="To Station" required>
      <input type="number" name="total_seats" class="form-control mb-3" placeholder="Total Seats" required>
      <input type="time" name="schedule_time" class="form-control mb-3" required>
      <button type="submit" class="btn btn-primary w-100">Add Train</button>
    </form>
  </div>
</body>
</html>
