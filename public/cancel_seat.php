<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}

include('../config/db.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ticket_id = $_POST['ticket_id'];
  $cnic = $_POST['cnic'];

  // Find the ticket using CNIC or ticket_id
  $query = "SELECT t.ticket_id, t.train_id
            FROM Ticket t
            WHERE ";

  if (!empty($ticket_id)) {
    $query .= "t.ticket_id = '$ticket_id'";
  } elseif (!empty($cnic)) {
    $query .= "t.cnic = '$cnic'";
  } else {
    $message = "Please provide Ticket ID or CNIC.";
  }

  if ($message == "") {
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
      $ticket = $result->fetch_assoc();
      $train_id = $ticket['train_id'];
      $id = $ticket['ticket_id'];

      // Delete ticket
      $conn->query("DELETE FROM Ticket WHERE ticket_id = $id");

      // Increase available seat
      $conn->query("UPDATE Train SET available_seats = available_seats + 1 WHERE train_id = $train_id");

      $message = "✅ Ticket cancelled successfully. Seat returned to available pool.";
    } else {
      $message = "No ticket found with the provided details.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cancel Seat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5" style="max-width: 600px;">
  <h3 class="text-center mb-4">🚫 Cancel Booked Seat</h3>
  <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
  <?php if ($message): ?>
    <div class="alert alert-info text-center"><?= $message ?></div>
  <?php endif; ?>
  <form method="POST">
    <input type="number" name="ticket_id" class="form-control mb-3" placeholder="Enter Ticket ID (optional)">
    <input type="text" name="cnic" class="form-control mb-3" placeholder="Or Enter CNIC (without dashes)">
    <button type="submit" class="btn btn-danger w-100">Cancel Seat</button>
  </form>
</div>
</body>
</html>
