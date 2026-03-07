<?php 
session_start();

if (!isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    header("Location: auth.php");
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Trains - Railway Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="styles.css?v=1.1" rel="stylesheet">
  <style>
    .page-header {
      text-align: center;
      padding: 30px 20px;
    }
    .page-title {
      font-size: 2rem;
      font-weight: 700;
      background: linear-gradient(135deg, #60a5fa, #f59e0b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 10px;
    }
    .page-subtitle {
      color: #94a3b8;
      font-size: 1rem;
    }
    .train-showcase {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    .train-showcase img {
      width: 200px;
      height: 120px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .back-btn {
      margin-bottom: 20px;
    }
    .table-card {
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
    }
    .table {
      margin: 0;
      background: #ffffff !important;
      color: #111827 !important;
    }
    .table thead {
      background: #e5e7eb !important;
    }
    .table thead th {
      color: #111827 !important;
      padding: 15px;
      font-weight: 600;
    }
    .table tbody td {
      color: #111827 !important;
      padding: 12px 15px;
      border-bottom: 1px solid #e5e7eb;
    }
    .table tbody tr:hover {
      background: #f3f4f6 !important;
    }
    .fare {
      color: #b45309;
      font-weight: 600;
    }
    .seats-available {
      color: #047857;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="container-fluid">
      <span class="navbar-brand">Railway Management</span>
      <div>
        <?php if (isset($_SESSION['admin_logged_in'])): ?>
          <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        <?php elseif (isset($_SESSION['user_logged_in'])): ?>
          <a href="user_dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">🚆 Available Trains</h1>
      <p class="page-subtitle">Choose your perfect train for your journey</p>
    </div>

    <!-- Train Icons Showcase -->
    <div class="train-showcase">
      <div class="card text-center" style="background: rgba(15,23,42,0.9); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; padding: 16px; min-width: 180px;">
        <div style="font-size: 2rem; margin-bottom: 4px;">🚄</div>
        <div>Express Service</div>
      </div>
      <div class="card text-center" style="background: rgba(15,23,42,0.9); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; padding: 16px; min-width: 180px;">
        <div style="font-size: 2rem; margin-bottom: 4px;">🚆</div>
        <div>Standard Service</div>
      </div>
      <div class="card text-center" style="background: rgba(15,23,42,0.9); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; padding: 16px; min-width: 180px;">
        <div style="font-size: 2rem; margin-bottom: 4px;">🚉</div>
        <div>Local Routes</div>
      </div>
    </div>

    <!-- Back Button -->
    <div class="back-btn">
      <?php if (isset($_SESSION['user_logged_in'])): ?>
        <a href="user_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
      <?php endif; ?>
      <?php if (isset($_SESSION['admin_logged_in'])): ?>
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
      <?php endif; ?>
    </div>

    <!-- Trains Table -->
    <div class="table-card">
      <table class="table">
        <thead>
          <tr>
            <th>Train Name</th>
            <th>From</th>
            <th>To</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Seats</th>
            <th>Fare (Rs.)</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><strong><?= htmlspecialchars($row['train_name'] ?? 'N/A') ?></strong></td>
              <td><?= htmlspecialchars($row['from_station'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($row['to_station'] ?? 'N/A') ?></td>
              <td>
                <?= isset($row['departure_time']) && $row['departure_time'] !== "" 
                  ? date('h:i A', strtotime($row['departure_time'])) 
                  : 'N/A' ?>
              </td>
              <td>
                <?= isset($row['arrival_time']) && $row['arrival_time'] !== "" 
                  ? date('h:i A', strtotime($row['arrival_time'])) 
                  : 'N/A' ?>
              </td>
              <td class="seats-available"><?= $row['available_seats'] ?? 'N/A' ?> / <?= $row['total_seats'] ?? 'N/A' ?></td>
              <td class="fare"><?= isset($row['fare']) ? number_format($row['fare'], 0) : 'N/A' ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

