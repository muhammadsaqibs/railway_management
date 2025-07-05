<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
include('../config/db.php');

$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $passenger_id = $_SESSION['user_id'];
  $train_id = $_POST['train_id'];
  $source = $_POST['source'];
  $destination = $_POST['destination'];
  $seat_no = rand(1, 100); // random seat for now
  $cnic = $_POST['cnic'];

  // Check available seats
  $check = "SELECT available_seats FROM Train WHERE train_id = $train_id";
  $train = $conn->query($check)->fetch_assoc();

  if ($train['available_seats'] > 0) {
    // Insert ticket
    $insert = "INSERT INTO Ticket (passenger_id, train_id, seat_no, source_city, destination_city, cnic)
               VALUES ('$passenger_id', '$train_id', '$seat_no', '$source', '$destination', '$cnic')";
    if ($conn->query($insert)) {
      // Decrement seat
      $conn->query("UPDATE Train SET available_seats = available_seats - 1 WHERE train_id = $train_id");

      // Get ticket id
      $ticket_id = $conn->insert_id;
      header("Location: payment.php?ticket_id=$ticket_id");
      exit;
    } else {
      $success = "Booking failed.";
    }
  } else {
    $success = "No seats available on this train.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Seat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width:600px;">
    <h3 class="mb-4 text-center">🎫 Book a Seat</h3>
    <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($success): ?>
      <div class="alert alert-danger text-center"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="number" name="train_id" class="form-control mb-3" placeholder="Train ID" required>
      <input type="text" name="source" class="form-control mb-3" placeholder="Source City" required>
      <input type="text" name="destination" class="form-control mb-3" placeholder="Destination City" required>
      <input type="text" name="cnic" class="form-control mb-3" placeholder="CNIC (without dashes)" required>
      <button type="submit" class="btn btn-success w-100">Continue to Payment</button>
    </form>
  </div>
</body>
</html>
