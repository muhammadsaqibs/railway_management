<?php
session_start();

// Debug - Check database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "railway_management";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Error: " . $conn->connect_error);
}

$error = "";
$success = "";

// Check for registration message
if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $success = "Registration successful! Please login.";
}

// Handle Login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = $_POST['user_type'];

    echo "<!-- Debug: email=$email, user_type=$user_type -->";

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } elseif ($user_type === 'admin') {
        // Admin login - check database credentials
        $admin_sql = "SELECT * FROM Admin WHERE username = '$email' AND password = '$password'";
        $admin_result = $conn->query($admin_sql);
        
        echo "<!-- Debug: admin query = $admin_sql -->";
        echo "<!-- Debug: admin rows = " . ($admin_result ? $admin_result->num_rows : 'null') . " -->";
        
        if ($admin_result && $admin_result->num_rows == 1) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $email;
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid admin username or password. <br>Check: " . $conn->error;
        }
    } else {
        // User login - check database
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            $sql = "SELECT * FROM Passenger WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($sql);
            
            echo "<!-- Debug: user query = $sql -->";
            echo "<!-- Debug: user rows = " . ($result ? $result->num_rows : 'null') . " -->";

            if ($result && $result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['passenger_id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: user_dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password. Please check your credentials. <br>Check: " . $conn->error;
            }
        }
    }
}

// Handle Registration - only email & password required (debug)
if (isset($_POST['register'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = "Please enter email and password.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        $check  = "SELECT * FROM Passenger WHERE email = '$email'";
        $result = $conn->query($check);

        if ($result && $result->num_rows > 0) {
            $error = "Email already registered. Please login.";
        } else {
            $sql = "INSERT INTO Passenger (email, password)
                    VALUES ('$email', '$password')";
            if ($conn->query($sql)) {
                $success = "Registration successful! Please login with your credentials.";
            } else {
                $error = "Registration failed. Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login / Register - Railway Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      font-family: Arial, sans-serif;
    }
    .auth-container {
      background: rgba(30, 41, 59, 0.95);
      border-radius: 20px;
      padding: 30px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .logo {
      text-align: center;
      font-size: 3rem;
      margin-bottom: 10px;
    }
    h1 {
      text-align: center;
      color: #60a5fa;
      font-size: 1.5rem;
      margin-bottom: 20px;
    }
    .tabs {
      display: flex;
      margin-bottom: 20px;
      background: #1e293b;
      border-radius: 10px;
      padding: 4px;
    }
    .tabs button {
      flex: 1;
      padding: 10px;
      border: none;
      background: transparent;
      color: #94a3b8;
      cursor: pointer;
      border-radius: 8px;
      font-weight: bold;
    }
    .tabs button.active {
      background: #3b82f6;
      color: white;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      color: #e2e8f0;
      margin-bottom: 5px;
      font-size: 0.9rem;
    }
    input, select {
      width: 100%;
      padding: 12px;
      background: #0f172a;
      border: 2px solid #334155;
      border-radius: 8px;
      color: white;
      font-size: 1rem;
    }
    input:focus, select:focus {
      outline: none;
      border-color: #3b82f6;
    }
    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
      margin-top: 10px;
    }
    .btn-primary {
      background: #3b82f6;
      color: white;
    }
    .alert {
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 15px;
      text-align: center;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.2);
      color: #f87171;
      border: 1px solid #ef4444;
    }
    .alert-success {
      background: rgba(16, 185, 129, 0.2);
      color: #34d399;
      border: 1px solid #10b981;
    }
    .form-section {
      display: none;
    }
    .form-section.active {
      display: block;
    }
    .back {
      text-align: center;
      margin-top: 15px;
    }
    .back a {
      color: #3b82f6;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="auth-container">
    <div class="logo">🚂</div>
    <h1>Railway Management</h1>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="tabs">
      <button type="button" class="active" onclick="showTab('login')">Login</button>
      <button type="button" onclick="showTab('register')">Register</button>
    </div>

    <form method="POST" id="login-form">
      <div class="form-group">
        <label>Account Type</label>
        <select name="user_type" onchange="toggleFields()">
          <option value="user">Passenger / User</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div id="user-fields" class="form-section active">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Enter your email">
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password">
        </div>
      </div>

      <div id="admin-fields" class="form-section">
        <div class="form-group">
          <label>Admin Username</label>
          <input type="text" name="email" placeholder="Enter admin username">
        </div>
        <div class="form-group">
          <label>Admin Password</label>
          <input type="password" name="password" placeholder="Enter admin password">
        </div>
      </div>

      <button type="submit" name="login" class="btn btn-primary">Login</button>
      <div class="back"><a href="home.php">← Back to Home</a></div>
    </form>

    <form method="POST" id="register-form" style="display:none;">
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Create password (min 4 characters)">
      </div>
      <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>
  </div>

  <script>
    function showTab(tab) {
      const buttons = document.querySelectorAll('.tabs button');
      const loginForm = document.getElementById('login-form');
      const registerForm = document.getElementById('register-form');
      
      if (tab === 'login') {
        buttons[0].classList.add('active');
        buttons[1].classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
      } else {
        buttons[0].classList.remove('active');
        buttons[1].classList.add('active');
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
      }
    }

    function toggleFields() {
      const userType = document.querySelector('select[name="user_type"]').value;
      const userFields = document.getElementById('user-fields');
      const adminFields = document.getElementById('admin-fields');
      
      if (userType === 'admin') {
        userFields.classList.remove('active');
        adminFields.classList.add('active');
      } else {
        userFields.classList.add('active');
        adminFields.classList.remove('active');
      }
    }
  </script>
</body>
</html>

