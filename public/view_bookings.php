<?php
session_start();
if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
include('../config/db.php');

$query = "
  SELECT 
    t.ticket_id,
    p.name AS passenger_name,
    t.cnic,
    tr.train_name,
    tr.from_station,
    tr.to_station,
    t.seat_no,
    t.source_city,
    t.destination_city,
    t.status
  FROM Ticket t
  JOIN Passenger p ON t.passenger_id = p.passenger_id
  JOIN Train tr ON t.train_id = tr.train_id
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Bookings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h3 class="text-center mb-4">📋 All Bookings</h3>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <table class="table table-bordered table-hover bg-white">
      <thead class="table-dark">
        <tr>
          <th>Ticket ID</th>
          <th>Passenger</th>
          <th>CNIC</th>
          <th>Train</th>
          <th>From</th>
          <th>To</th>
          <th>Seat No</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['ticket_id'] ?></td>
            <td><?= $row['passenger_name'] ?></td>
            <td><?= $row['cnic'] ?></td>
            <td><?= $row['train_name'] ?></td>
            <td><?= $row['source_city'] ?></td>
            <td><?= $row['destination_city'] ?></td>
            <td><?= $row['seat_no'] ?></td>
            <td><?= $row['status'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
