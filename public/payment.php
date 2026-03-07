<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}

include('../config/db.php');

if (!isset($_GET['ticket_id'])) {
    echo "Invalid access.";
    exit;
}

$ticket_id = $_GET['ticket_id'];
$message = "";
$ticket = null;

// Get ticket details
$stmt_check = $conn->prepare("SELECT t.*, tr.train_name, tr.departure_time, tr.arrival_time, tr.fare FROM ticket t JOIN train tr ON t.train_id = tr.train_id WHERE t.ticket_id = ?");
$stmt_check->bind_param("i", $ticket_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    echo "⚠ Ticket not found. Please book a ticket first.";
    exit;
}

$ticket = $result->fetch_assoc();
$fare = $ticket['fare'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['payment_method'];

    // Insert payment safely using prepared statement
    $stmt = $conn->prepare("INSERT INTO Payment (ticket_id, amount, payment_method, payment_status) VALUES (?, ?, ?, 'Paid')");
    $stmt->bind_param("iis", $ticket_id, $fare, $method);

    if ($stmt->execute()) {
        // Update ticket status to Booked and decrease available seats
        $conn->query("UPDATE Ticket SET status = 'Booked' WHERE ticket_id = $ticket_id");
        $conn->query("UPDATE Train SET available_seats = available_seats - 1 WHERE train_id = " . $ticket['train_id']);
        $message = "🎉 Payment successful! Seat booked.";
    } else {
        $message = "❌ Payment failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="max-width:600px;">
    <h3 class="mb-4 text-center text-white">💳 Payment</h3>
    <?php if ($message): ?>
      <div class="alert alert-success text-center"><?= $message ?></div>
      <!-- Ticket Receipt -->
      <div class="card mb-4" style="background: rgba(30, 41, 59, 0.9);">
        <div class="card-body text-white">
          <h5 class="card-title text-center text-info mb-4">🎫 Ticket Receipt</h5>
          <div class="row mb-2">
            <div class="col-6 text-muted">Ticket Token:</div>
            <div class="col-6 fw-bold text-success"><?= $ticket['ticket_token'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Train:</div>
            <div class="col-6 fw-bold"><?= $ticket['train_name'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">From:</div>
            <div class="col-6 fw-bold"><?= $ticket['source_city'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">To:</div>
            <div class="col-6 fw-bold"><?= $ticket['destination_city'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Departure:</div>
            <div class="col-6 fw-bold"><?= $ticket['departure_time'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Arrival:</div>
            <div class="col-6 fw-bold"><?= $ticket['arrival_time'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Seat No:</div>
            <div class="col-6 fw-bold"><?= $ticket['seat_no'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Fare:</div>
            <div class="col-6 fw-bold text-warning">Rs. <?= $fare ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">CNIC:</div>
            <div class="col-6 fw-bold"><?= $ticket['cnic'] ?></div>
          </div>
          <div class="row mb-2">
            <div class="col-6 text-muted">Booking Time:</div>
            <div class="col-6 fw-bold"><?= $ticket['booking_time'] ?></div>
          </div>
          <hr style="border-color: rgba(255,255,255,0.2);">
          <p class="text-center text-warning small mt-3"><i class="fa-solid fa-circle-exclamation"></i> <strong>Important Notice:</strong> Please arrive at the station at least 10 minutes before the departure time for a smooth boarding experience.</p>
        </div>
      </div>
    <?php else: ?>
    <div class="card mb-4">
      <div class="card-body">
        <h5>Total Fare: Rs. <?= $fare ?></h5>
        <form method="POST">
          <label class="form-label mt-3">Payment Method</label>
          <select class="form-select mb-3" name="payment_method" required>
            <option value="">Select</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
          </select>
          <button type="submit" class="btn btn-success w-100">Confirm Payment</button>
        </form>
      </div>
    </div>
    <?php endif; ?>
    <a href="user_dashboard.php" class="btn btn-secondary w-100">Return to Dashboard</a>
  </div>
</body>
</html>
