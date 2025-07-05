<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card:hover {
      transform: scale(1.02);
      transition: 0.3s;
    }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <span class="navbar-brand">Welcome Admin</span>
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </nav>

  <div class="container mt-5 text-center">
    <h3 class="mb-4">🛠️ Admin Dashboard</h3>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <a href="add_train.php" class="text-decoration-none">
          <div class="card bg-primary text-white shadow">
            <div class="card-body">
              <h4>Add Train</h4>
              <p>Insert new train details.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="view_trains.php" class="text-decoration-none">
          <div class="card bg-info text-white shadow">
            <div class="card-body">
              <h4>View Trains</h4>
             
              <p>See all available trains.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="view_bookings.php" class="text-decoration-none">
          <div class="card bg-success text-white shadow">
            <div class="card-body">
              <h4>View Bookings</h4>
              <p>Check all booked tickets.</p>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-4">
        <a href="delete_train.php" class="text-decoration-none">
          <div class="card bg-danger text-white shadow">
            <div class="card-body">
              <h4>Delete Train</h4>
              <p>Delete a train by name.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
