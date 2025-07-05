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
$fare = 1000;
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $method = $_POST['payment_method'];
  
  $sql = "INSERT INTO Payment (ticket_id, amount, payment_method)
          VALUES ('$ticket_id', '$fare', '$method')";

  if ($conn->query($sql)) {
    $message = "🎉 Seat booked successfully! Please take a screenshot of this page.";
  } else {
    $message = "Payment failed. Try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width:600px;">
    <h3 class="mb-4 text-center">💳 Payment</h3>
    <?php if ($message): ?>
      <div class="alert alert-success text-center"><?= $message ?></div>
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
