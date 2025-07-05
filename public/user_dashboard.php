<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card:hover {
      transform: scale(1.02);
      transition: 0.3s ease-in-out;
    }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <span class="navbar-brand">Welcome, <?= $_SESSION['user_name'] ?></span>
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </nav>

  <div class="container mt-5 text-center">
    <h3 class="mb-4">🎛️ User Dashboard</h3>
    <div class="row g-4 justify-content-center">
      <div class="col-md-5">
        <a href="view_trains.php" class="text-decoration-none">
          <div class="card text-white bg-primary shadow">
            <div class="card-body">
              <h4 class="card-title">Available Trains</h4>
              <p class="card-text">Check and explore trains.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-5">
  <a href="cancel_seat.php" class="text-decoration-none">
    <div class="card text-white bg-danger shadow">
      <div class="card-body">
        <h4 class="card-title">Cancel Seat</h4>
        <p class="card-text">Cancel your booking using CNIC or Ticket ID.</p>
      </div>
    </div>
  </a>
</div>

      <div class="col-md-5">
        <a href="book_seat.php" class="text-decoration-none">
          <div class="card text-white bg-success shadow">
            <div class="card-body">
              <h4 class="card-title">Book a Seat</h4>
              <p class="card-text">Fill info to reserve your seat.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
