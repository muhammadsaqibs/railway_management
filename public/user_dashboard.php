<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: auth.php");
    exit;
}
include('../config/db.php');

$booking_message = "";
if (isset($_GET['booking']) && $_GET['booking'] == 'success' && isset($_SESSION['booking_success'])) {
    $booking = $_SESSION['booking_success'];
    $booking_message = "
        <div class='alert alert-success'>
            <h4 class='text-center mb-3'>🎉 Booking Successful!</h4>
            <div class='text-center'>
                <p>Your ticket has been booked successfully.</p>
                <div class='card mt-3'>
                    <div class='card-body'>
                        <h5>Ticket Details</h5>
                        <p><strong>Ticket Token:</strong> <span class='text-primary'>{$booking['ticket_token']}</span></p>
                        <p><strong>Booking Time:</strong> " . date('d M Y, H:i', strtotime($booking['booking_time'])) . "</p>
                        <p><strong>Train ID:</strong> {$booking['train_id']}</p>
                        <p><strong>Route:</strong> {$booking['source']} → {$booking['destination']}</p>
                        <p><strong>Seat No:</strong> {$booking['seat_no']}</p>
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard - Railway Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
  <style>
    .hero {
      text-align: center;
      padding: 30px 20px;
    }
    .hero h1 {
      font-size: 2rem;
      background: linear-gradient(135deg, #60a5fa, #f59e0b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
    }
    .hero p {
      color: #94a3b8;
    }
    .train-banner {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
    }
    .train-banner img {
      width: 250px;
      height: 140px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      transition: transform 0.3s ease;
    }
    .train-banner img:hover {
      transform: scale(1.05);
    }
    .dashboard-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      max-width: 900px;
      margin: 0 auto;
    }
    .card {
      background: linear-gradient(135deg, #1e3a8a, #3b82f6);
      border-radius: 16px;
      padding: 25px;
      text-align: center;
      color: white;
      text-decoration: none;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }
    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(59, 130, 246, 0.5);
    }
    .card .icon {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }
    .card h4 {
      margin: 0;
      font-size: 1.1rem;
    }
    .card p {
      color: rgba(255,255,255,0.8);
      font-size: 0.85rem;
      margin: 5px 0 0 0;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid #10b981;
      color: #34d399;
      border-radius: 12px;
      padding: 20px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-fluid">
      <span class="navbar-brand">User Dashboard</span>
      <div>
        <span class="text-muted me-3">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Hero Section -->
    <div class="hero">
      <h1>🎛️ User Dashboard</h1>
      <p>Manage your train bookings and travel plans</p>
    </div>

    <!-- Train Banner -->
    <div class="train-banner">
      <img src="images/train1.png" alt="Train 1">
      <img src="images/train2.png" alt="Train 2">
      <img src="images/train3.png" alt="Train 3">
    </div>

    <?= $booking_message ?>

    <!-- Dashboard Grid -->
    <div class="dashboard-grid">
      <a href="view_trains.php" class="card">
        <div class="icon">🚂</div>
        <h4>Available Trains</h4>
        <p>Check and explore trains</p>
      </a>
      
      <a href="book_seat.php" class="card">
        <div class="icon">🎫</div>
        <h4>Book a Seat</h4>
        <p>Reserve your train ticket</p>
      </a>
      
      <a href="cancel_seat.php" class="card">
        <div class="icon">❌</div>
        <h4>Cancel Seat</h4>
        <p>Cancel your booking</p>
      </a>
      
      <a href="view_bookings.php" class="card">
        <div class="icon">📋</div>
        <h4>My Bookings</h4>
        <p>View your booking history</p>
      </a>
    </div>
  </div>
</body>
</html>

