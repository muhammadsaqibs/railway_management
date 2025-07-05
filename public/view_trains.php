<?php
session_start();
if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
include('../config/db.php');
$query = "SELECT * FROM Train";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Trains</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h3 class="mb-4 text-center">🚆 Available Trains</h3>
    <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back to user Dashboard</a>
     <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to admin Dashboard</a>
    <table class="table table-bordered table-hover bg-white">
      <thead class="table-dark">
        <tr>
          <th>Train ID</th>
          <th>Train Name</th>
          <th>From</th>
          <th>To</th>
          <th>Total Seats</th>
          <th>Available Seats</th>
          <th>Schedule Time</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['train_id'] ?></td>
            <td><?= $row['train_name'] ?></td>
            <td><?= $row['from_station'] ?></td>
            <td><?= $row['to_station'] ?></td>
            <td><?= $row['total_seats'] ?></td>
            <td><?= $row['available_seats'] ?></td>
            <td><?= date('h:i A', strtotime($row['schedule_time'])) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
