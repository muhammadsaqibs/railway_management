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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_trains'])) {
  // Fetch available trains based on source and destination
  $query = "SELECT * FROM Train WHERE from_station = '$selected_source' AND to_station = '$selected_destination' AND available_seats > 0";
  $result = $conn->query($query);
  $trains = $result->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_ticket'])) {
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
    // Generate unique ticket token
    $ticket_token = 'TKT-' . strtoupper(substr(md5(uniqid()), 0, 8));
    $booking_time = date('Y-m-d H:i:s');

    // Insert ticket with token and time
    $insert = "INSERT INTO Ticket (passenger_id, train_id, seat_no, source_city, destination_city, cnic, ticket_token, booking_time, status)
               VALUES ('$passenger_id', '$train_id', '$seat_no', '$source', '$destination', '$cnic', '$ticket_token', '$booking_time', 'Booked')";
    if ($conn->query($insert)) {
      // Decrement seat
      $conn->query("UPDATE Train SET available_seats = available_seats - 1 WHERE train_id = $train_id");

      // Get ticket id
      $ticket_id = $conn->insert_id;
      $_SESSION['booking_success'] = [
        'ticket_token' => $ticket_token,
        'booking_time' => $booking_time,
        'train_id' => $train_id,
        'source' => $source,
        'destination' => $destination,
        'seat_no' => $seat_no
      ];
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
</head>
<body>
  <div class="container" style="max-width:800px;">
    <h3 class="mb-4 text-center text-white">🎫 Book a Seat</h3>
    <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($success): ?>
      <div class="alert alert-danger text-center"><?= $success ?></div>
    <?php endif; ?>

    <!-- Search Trains Form -->
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title text-white">🔍 Search Available Trains</h5>
        <form method="POST" class="row g-3">
          <div class="col-md-5">
            <label class="form-label text-white">From</label>
            <select name="source" class="form-select" required>
              <option value="">Select Source</option>
              <option value="Karachi" <?= $selected_source == 'Karachi' ? 'selected' : '' ?>>Karachi</option>
              <option value="Lahore" <?= $selected_source == 'Lahore' ? 'selected' : '' ?>>Lahore</option>
              <option value="Islamabad" <?= $selected_source == 'Islamabad' ? 'selected' : '' ?>>Islamabad</option>
              <option value="Multan" <?= $selected_source == 'Multan' ? 'selected' : '' ?>>Multan</option>
              <option value="Rawalpindi" <?= $selected_source == 'Rawalpindi' ? 'selected' : '' ?>>Rawalpindi</option>
            </select>
          </div>
          <div class="col-md-5">
            <label class="form-label text-white">To</label>
            <select name="destination" class="form-select" required>
              <option value="">Select Destination</option>
              <option value="Karachi" <?= $selected_destination == 'Karachi' ? 'selected' : '' ?>>Karachi</option>
              <option value="Lahore" <?= $selected_destination == 'Lahore' ? 'selected' : '' ?>>Lahore</option>
              <option value="Islamabad" <?= $selected_destination == 'Islamabad' ? 'selected' : '' ?>>Islamabad</option>
              <option value="Multan" <?= $selected_destination == 'Multan' ? 'selected' : '' ?>>Multan</option>
              <option value="Rawalpindi" <?= $selected_destination == 'Rawalpindi' ? 'selected' : '' ?>>Rawalpindi</option>
            </select>
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" name="search_trains" class="btn btn-primary w-100">Search</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Available Trains -->
    <?php if (!empty($trains)): ?>
      <div class="card mb-4">
        <div class="card-body">
          <h5 class="card-title text-white">🚂 Available Trains</h5>
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Train Name</th>
                  <th>Departure</th>
                  <th>Arrival</th>
                  <th>Available Seats</th>
                  <th>Fare</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($trains as $train): ?>
                  <tr>
                    <td class="text-white"><?= htmlspecialchars($train['train_name']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($train['departure_time']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($train['arrival_time']) ?></td>
                    <td class="text-white"><?= htmlspecialchars($train['available_seats']) ?></td>
                    <td class="text-white">Rs. <?= htmlspecialchars($train['fare']) ?></td>
                    <td>
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="train_id" value="<?= $train['train_id'] ?>">
                        <input type="hidden" name="source" value="<?= $selected_source ?>">
                        <input type="hidden" name="destination" value="<?= $selected_destination ?>">
                        <input type="text" name="cnic" class="form-control mb-2" placeholder="CNIC (without dashes)" required>
                        <button type="submit" name="book_ticket" class="btn btn-success btn-sm">Book Now</button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_trains'])): ?>
      <div class="alert alert-warning text-center">No trains available for the selected route.</div>
    <?php endif; ?>
  </div>
</body>
</html>
