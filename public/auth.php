<?php
session_start();

// Database connection
include('../config/db.php');

$error = "";
$success = "";

// Check for registration message
if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $success = "Registration successful! Please login.";
}

// Handle Login
if (isset($_POST['login'])) {
    $user_type = $_POST['user_type'];
    
    if ($user_type === 'admin') {
        $username = trim($_POST['username']);
        $password = trim($_POST['admin_password'] ?? '');
        
        if (empty($username)) {
            $error = "Admin Username is required.";
        } elseif (empty($password)) {
            $error = "Admin Password is required.";
        } else {
            // Admin login
            $admin_sql = "SELECT * FROM Admin WHERE username = '$username' AND password = '$password'";
            $admin_result = $conn->query($admin_sql);
            
            if ($admin_result && $admin_result->num_rows == 1) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                header("Location: admin_dashboard.php");
                exit;
            } else {
                $error = "Invalid admin username or password.";
            }
        }
    } else {
        $email = trim($_POST['email']);
        $password = trim($_POST['user_password'] ?? '');
        
        if (empty($email)) {
            $error = "User Email Address is required.";
        } elseif (empty($password)) {
            $error = "User Password is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            // User login
            $sql = "SELECT * FROM Passenger WHERE email = '$email' AND password = '$password'";
            $result = $conn->query($sql);

            if ($result && $result->num_rows == 1) {
                $user = $result->fetch_assoc();
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['passenger_id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: user_dashboard.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
    }
}

// Handle Registration - only email & password required
if (isset($_POST['register'])) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $error = "Registration Email is required.";
    } elseif (empty($password)) {
        $error = "Registration Password is required.";
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
            // Name/phone/cnic optional; store only email & password
            $sql = "INSERT INTO Passenger (email, password)
                    VALUES ('$email', '$password')";
            if ($conn->query($sql)) {
                $success = "Registration successful! Please login.";
            } else {
                $error = "Registration failed. Please try again.";
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
    .btn-primary:hover {
      background: #2563eb;
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
      <div class="alert alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
      <div class="alert alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="tabs">
      <button type="button" class="active" onclick="showTab('login')">Login</button>
      <button type="button" onclick="showTab('register')">Register</button>
    </div>

    <!-- Login Form -->
    <form method="POST" id="login-form">
      <div class="form-group">
        <label>Account Type</label>
        <select name="user_type" onchange="toggleFields()">
          <option value="user">Passenger / User</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <!-- User Login Fields -->
      <div id="user-fields" class="form-section active">
        <div class="form-group">
          <label>Email</label>
          <input type="email" id="user_email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" id="user_pass" name="user_password" placeholder="Enter your password" required>
        </div>
      </div>

      <!-- Admin Login Fields -->
      <div id="admin-fields" class="form-section">
        <div class="form-group">
          <label>Admin Username</label>
          <input type="text" id="admin_user" name="username" placeholder="Enter admin username">
        </div>
        <div class="form-group">
          <label>Admin Password</label>
          <input type="password" id="admin_pass" name="admin_password" placeholder="Enter admin password">
        </div>
      </div>

      <button type="submit" name="login" class="btn btn-primary">Login</button>
      <div class="back"><a href="home.php">← Back to Home</a></div>
    </form>

    <!-- Register Form -->
    <form method="POST" id="register-form" style="display:none;">
      <div class="form-group">
        <label>Email</label>
        <input type="email" id="reg_email" name="email" placeholder="Enter your email">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" id="reg_pass" name="password" placeholder="Create password (min 4 characters)">
      </div>
      <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>
  </div>

  <script>
    function showTab(tab) {
      const buttons = document.querySelectorAll('.tabs button');
      const loginForm = document.getElementById('login-form');
      const registerForm = document.getElementById('register-form');
      
      const regEmail = document.getElementById('reg_email');
      const regPass = document.getElementById('reg_pass');
      
      if (tab === 'login') {
        buttons[0].classList.add('active');
        buttons[1].classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        
        regEmail.removeAttribute('required');
        regPass.removeAttribute('required');
        regEmail.disabled = true;
        regPass.disabled = true;

        toggleFields(); // Ensure correct login fields are active and required
      } else {
        buttons[0].classList.remove('active');
        buttons[1].classList.add('active');
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        
        regEmail.setAttribute('required', 'true');
        regPass.setAttribute('required', 'true');
        regEmail.disabled = false;
        regPass.disabled = false;
        
        // Remove required and disable all login fields
        const loginInputs = [
            document.getElementById('user_email'),
            document.getElementById('user_pass'),
            document.getElementById('admin_user'),
            document.getElementById('admin_pass')
        ];
        
        loginInputs.forEach(input => {
            if(input) {
                input.removeAttribute('required');
                input.disabled = true;
            }
        });
      }
    }

    function toggleFields() {
      // Only run this if we are on the login form
      if(document.getElementById('login-form').style.display === 'none') return;
      
      const userType = document.querySelector('select[name="user_type"]').value;
      const userFields = document.getElementById('user-fields');
      const adminFields = document.getElementById('admin-fields');
      
      const userEmail = document.getElementById('user_email');
      const userPass = document.getElementById('user_pass');
      const adminUser = document.getElementById('admin_user');
      const adminPass = document.getElementById('admin_pass');
      
      if (userType === 'admin') {
        userFields.classList.remove('active');
        adminFields.classList.add('active');
        
        adminUser.setAttribute('required', 'true');
        adminPass.setAttribute('required', 'true');
        adminUser.disabled = false;
        adminPass.disabled = false;
        
        userEmail.removeAttribute('required');
        userPass.removeAttribute('required');
        userEmail.disabled = true;
        userPass.disabled = true;
      } else {
        userFields.classList.add('active');
        adminFields.classList.remove('active');
        
        userEmail.setAttribute('required', 'true');
        userPass.setAttribute('required', 'true');
        userEmail.disabled = false;
        userPass.disabled = false;
        
        adminUser.removeAttribute('required');
        adminPass.removeAttribute('required');
        adminUser.disabled = true;
        adminPass.disabled = true;
      }
    }
    
    // Initialize properly on load
    document.addEventListener("DOMContentLoaded", function() {
        showTab('login');
    });
  </script>
</body>
</html>

