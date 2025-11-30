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
    t.ticket_token,
    p.name AS passenger_name,
    t.cnic,
    tr.train_name,
    tr.from_station,
    tr.to_station,
    t.seat_no,
    t.source_city,
    t.destination_city,
    t.booking_time,
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
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h3 class="text-center mb-4 text-white">📋 All Bookings</h3>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Ticket ID</th>
                <th>Ticket Token</th>
                <th>Passenger</th>
                <th>CNIC</th>
                <th>Train</th>
                <th>From</th>
                <th>To</th>
                <th>Seat No</th>
                <th>Booking Time</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="text-white"><?= $row['ticket_id'] ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['ticket_token']) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['passenger_name']) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['cnic']) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['train_name']) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['source_city']) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['destination_city']) ?></td>
                  <td class="text-white"><?= $row['seat_no'] ?></td>
                  <td class="text-white"><?= date('d M Y, H:i', strtotime($row['booking_time'])) ?></td>
                  <td class="text-white"><?= htmlspecialchars($row['status']) ?></td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
