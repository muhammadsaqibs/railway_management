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
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="max-width:600px;">
    <h3 class="text-center mb-4 text-white">➕ Add New Train</h3>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($success): ?>
      <div class="alert alert-info text-center"><?= $success ?></div>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <form method="POST">
          <div class="mb-3">
            <label class="form-label text-white">Train Name</label>
            <input type="text" name="train_name" class="form-control" placeholder="Train Name" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-white">From Station</label>
            <select name="from_station" class="form-select" required>
              <option value="">Select From Station</option>
              <option value="Karachi">Karachi</option>
              <option value="Lahore">Lahore</option>
              <option value="Islamabad">Islamabad</option>
              <option value="Multan">Multan</option>
              <option value="Rawalpindi">Rawalpindi</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-white">To Station</label>
            <select name="to_station" class="form-select" required>
              <option value="">Select To Station</option>
              <option value="Karachi">Karachi</option>
              <option value="Lahore">Lahore</option>
              <option value="Islamabad">Islamabad</option>
              <option value="Multan">Multan</option>
              <option value="Rawalpindi">Rawalpindi</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label text-white">Total Seats</label>
            <input type="number" name="total_seats" class="form-control" placeholder="Total Seats" required>
          </div>
          <div class="mb-3">
            <label class="form-label text-white">Schedule Time</label>
            <input type="time" name="schedule_time" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Add Train</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
