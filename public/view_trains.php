<?php 
session_start();

if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: user_login.php");
    exit;
}

include('../config/db.php');

// Get all trains
$query = "SELECT * FROM Train";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Available Trains</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h3 class="mb-4 text-center text-white">🚆 Available Trains</h3>

    <?php if (isset($_SESSION['user_logged_in'])): ?>
      <a href="user_dashboard.php" class="btn btn-secondary mb-3">← Back to User Dashboard</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin_logged_in'])): ?>
      <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Admin Dashboard</a>
    <?php endif; ?>

    <div class="card">
      <div class="card-body">
        <div class="table-responsive">

          <table class="table">
            <thead>
              <tr>
                <th>Train ID</th>
                <th>Train Name</th>
                <th>From</th>
                <th>To</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Total Seats</th>
                <th>Available Seats</th>
                <th>Fare (Rs.)</th>
              </tr>
            </thead>

            <tbody>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>

                  <td class="text-dark"><?= $row['train_id'] ?></td>

                  <td class="text-dark"><?= htmlspecialchars($row['train_name'] ?? 'N/A') ?></td>

                  <td class="text-dark"><?= htmlspecialchars($row['from_station'] ?? 'N/A') ?></td>

                  <td class="text-dark"><?= htmlspecialchars($row['to_station'] ?? 'N/A') ?></td>

                  <!-- Departure -->
                  <td class="text-dark">
                    <?= isset($row['departure_time']) && $row['departure_time'] !== "" 
                      ? date('h:i A', strtotime($row['departure_time'])) 
                      : 'N/A' ?>
                  </td>

                  <!-- Arrival -->
                  <td class="text-dark">
                    <?= isset($row['arrival_time']) && $row['arrival_time'] !== "" 
                      ? date('h:i A', strtotime($row['arrival_time'])) 
                      : 'N/A' ?>
                  </td>

                  <td class="text-dark"><?= $row['total_seats'] ?? 'N/A' ?></td>

                  <td class="text-dark"><?= $row['available_seats'] ?? 'N/A' ?></td>

                  <!-- Fare -->
                  <td class="text-dark">
                    <?= isset($row['fare']) && $row['fare'] !== "" 
                      ? number_format($row['fare'], 2) 
                      : 'N/A' ?>
                  </td>

                </tr>
              <?php endwhile; ?>
            </tbody>

          </table>

        </div>
      </div>
    </div>

  </div>
</body>
</html>
