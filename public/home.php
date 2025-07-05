<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Railway Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card:hover {
      transform: scale(1.03);
      transition: 0.3s;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container text-center mt-5">
    <h2 class="mb-4">🚄 Railway Management System</h2>
    <div class="row justify-content-center g-4">
      <div class="col-md-4">
        <a href="user_signup.php" class="text-decoration-none">
          <div class="card shadow bg-primary text-white">
            <div class="card-body">
              <h4>User Sign Up</h4>
              <p>Register to book your train tickets.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-4">
        <a href="admin_login.php" class="text-decoration-none">
          <div class="card shadow bg-dark text-white">
            <div class="card-body">
              <h4>Admin Login</h4>
              <p>Login to manage trains and view bookings.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
