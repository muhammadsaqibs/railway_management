<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
include('../config/db.php');

$booking_message = "";
if (isset($_GET['booking']) && $_GET['booking'] == 'success' && isset($_SESSION['booking_success'])) {
    $booking = $_SESSION['booking_success'];
    $booking_message = "
        <div class='alert alert-success text-center mb-4'>
            <h4>🎉 Booking Successful!</h4>
            <p>Your ticket has been booked successfully.</p>
            <div class='row justify-content-center'>
                <div class='col-md-6'>
                    <div class='card'>
                        <div class='card-body text-center'>
                            <h5>Ticket Details</h5>
                            <p><strong>Ticket Token:</strong> {$booking['ticket_token']}</p>
                            <p><strong>Booking Time:</strong> " . date('d M Y, H:i', strtotime($booking['booking_time'])) . "</p>
                            <p><strong>Train ID:</strong> {$booking['train_id']}</p>
                            <p><strong>Route:</strong> {$booking['source']} → {$booking['destination']}</p>
                            <p><strong>Seat No:</strong> {$booking['seat_no']}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    ";
    unset($_SESSION['booking_success']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <span class="navbar-brand">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </nav>

  <div class="container">
    <h3 class="mb-4 text-center text-white">🎛️ User Dashboard</h3>

    <?= $booking_message ?>

    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <a href="view_trains.php" class="text-decoration-none">
          <div class="card">
            <div class="card-body text-center">
              <h4>🚂 Available Trains</h4>
              <p>Check and explore trains.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="book_seat.php" class="text-decoration-none">
          <div class="card">
            <div class="card-body text-center">
              <h4>🎫 Book a Seat</h4>
              <p>Reserve your train ticket.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="cancel_seat.php" class="text-decoration-none">
          <div class="card">
            <div class="card-body text-center">
              <h4>❌ Cancel Seat</h4>
              <p>Cancel your booking.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
