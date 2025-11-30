<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: user_login.php");
    exit;
}
include('../config/db.php');

$success = "";
$trains = [];
$selected_source = $_POST['source'] ?? '';
$selected_destination = $_POST['destination'] ?? '';

// Search Trains
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_trains'])) {
  $query = "SELECT * FROM Train 
            WHERE from_station = '$selected_source' 
            AND to_station = '$selected_destination' 
            AND available_seats > 0";

  $result = $conn->query($query);
  $trains = $result->fetch_all(MYSQLI_ASSOC);
}

// Booking Train
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ticket'])) {
  $passenger_id = $_SESSION['user_id'];
  $train_id = $_POST['train_id'];
  $source = $_POST['source'];
  $destination = $_POST['destination'];
  $seat_no = rand(1, 100);
  $cnic = $_POST['cnic'];

  $check = "SELECT available_seats FROM Train WHERE train_id = $train_id";
  $train = $conn->query($check)->fetch_assoc();

  if ($train['available_seats'] > 0) {
      $ticket_token = 'TKT-' . strtoupper(substr(md5(uniqid()), 0, 8));
      $booking_time = date('Y-m-d H:i:s');

      $insert = "INSERT INTO Ticket (passenger_id, train_id, seat_no, source_city, destination_city, cnic, ticket_token, booking_time, status)
                 VALUES ('$passenger_id', '$train_id', '$seat_no', '$source', '$destination', '$cnic', '$ticket_token', '$booking_time', 'Booked')";

      if ($conn->query($insert)) {
        $conn->query("UPDATE Train SET available_seats = available_seats - 1 WHERE train_id = $train_id");
        header("Location: user_dashboard.php?booking=success");
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
<link href="styles.css" rel="stylesheet">

<style>
/* ---- Smaller Card ---- */
.card {
    transform: scale(0.92);
    margin: auto;
    background: rgba(255,255,255,0.12) !important;
}

/* ---- Table Colors & Readability ---- */
.table {
    background: rgba(0,0,0,0.4);
    color: #faf7f7ff;
}

.table thead th {
    background: rgba(118, 75, 162, 0.9);
    color: #fff;
}

.table tbody tr:hover {
    background: rgba(255,255,255,0.08);
}

.table td {
    color: #0f0e0eff !important;
}

/* ---- White Soft Text ---- */
.text-white, label {
    color: #f1f1f1 !important;
}
</style>

</head>
<body>

<div class="container" style="max-width:800px;">
    <h3 class="mb-4 text-center text-white">🎫 Book a Seat</h3>
    <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back</a>

    <?php if ($success): ?>
        <div class="alert alert-danger text-center"><?= $success ?></div>
    <?php endif; ?>

    <!-- Search -->
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title text-white">🔍 Search Trains</h5>

            <form method="POST" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">From</label>
                    <select name="source" class="form-select" required>
                        <option value="">Select</option>
                        <?php
                          $cities = ["Karachi","Lahore","Islamabad","Multan","Rawalpindi"];
                          foreach($cities as $c){
                            $sel = $selected_source == $c ? "selected" : "";
                            echo "<option value='$c' $sel>$c</option>";
                          }
                        ?>
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label">To</label>
                    <select name="destination" class="form-select" required>
                        <option value="">Select</option>
                        <?php
                          foreach($cities as $c){
                            $sel = $selected_destination == $c ? "selected" : "";
                            echo "<option value='$c' $sel>$c</option>";
                          }
                        ?>
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" name="search_trains" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Trains Table -->
    <?php if (!empty($trains)): ?>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-white">🚂 Available Trains</h5>

            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>Train</th>
                            <th>Depart</th>
                            <th>Arrive</th>
                            <th>Seats</th>
                            <th>Fare</th>
                            <th>Book</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trains as $train): ?>
                        <tr>
                            <td><?= $train['train_name'] ?></td>
                            <td><?= $train['departure_time'] ?></td>
                            <td><?= $train['arrival_time'] ?></td>
                            <td><?= $train['available_seats'] ?></td>
                            <td>Rs. <?= $train['fare'] ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="train_id" value="<?= $train['train_id'] ?>">
                                    <input type="hidden" name="source" value="<?= $selected_source ?>">
                                    <input type="hidden" name="destination" value="<?= $selected_destination ?>">
                                    <input type="text" name="cnic" class="form-control mb-2" placeholder="CNIC" required>
                                    <button type="submit" name="book_ticket" class="btn btn-success btn-sm">Book</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <?php elseif(isset($_POST['search_trains'])): ?>
        <div class="alert alert-warning text-center">No trains found.</div>
    <?php endif; ?>

</div>

</body>
</html>
