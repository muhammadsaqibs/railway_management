<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Railway Management System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
min-height:100vh;
font-family:'Segoe UI',sans-serif;
color:white;
}

.navbar{
background: rgba(0,0,0,0.4);
backdrop-filter: blur(10px);
}

.navbar-brand{
font-weight:600;
font-size:22px;
}

.hero{
text-align:center;
padding:80px 20px 50px 20px;
}

.hero-icon{
font-size:70px;
display:block;
margin-bottom:20px;
}

.hero h1{
font-size:40px;
font-weight:700;
}

.hero p{
color:#ddd;
max-width:650px;
margin:auto;
margin-top:15px;
}

.dashboard-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:25px;
margin-top:30px;
}

.card{
border:none;
border-radius:20px;
background: rgba(255,255,255,0.1);
backdrop-filter: blur(15px);
color:white;
transition:0.3s;
text-decoration:none;
}

.card:hover{
transform: translateY(-8px);
box-shadow:0 15px 35px rgba(0,0,0,0.4);
}

.card-body{
padding:30px;
text-align:center;
}

.card-icon{
font-size:45px;
margin-bottom:15px;
}

.card h4{
font-weight:600;
}

.card p{
font-size:14px;
color:#ddd;
}

.features-title{
text-align:center;
margin-top:60px;
margin-bottom:30px;
font-weight:600;
}

.footer{
text-align:center;
margin-top:60px;
padding-bottom:20px;
font-size:14px;
color:#ccc;
}

</style>

</head>

<body class="theme-home">

<!-- Navbar -->

<nav class="navbar navbar-dark">
<div class="container-fluid">

<span class="navbar-brand">
<i class="fa-solid fa-train"></i> Railway Management
</span>

<div>
<a href="auth.php" class="btn btn-outline-light btn-sm">
<i class="fa-solid fa-right-to-bracket"></i> Login / Register
</a>
</div>

</div>
</nav>


<!-- Hero Section -->

<div class="container">

<div class="hero-slider-container">
  <div class="hero-slider-track">
    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=1600&q=80');"></div>
    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1541427468627-a89a96e5ca1d?auto=format&fit=crop&w=1600&q=80');"></div>
    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1563206767-5b18f218e8de?auto=format&fit=crop&w=1600&q=80');"></div>
    <!-- Clone first slide for seamless loop -->
    <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1474487548417-781cb71495f3?auto=format&fit=crop&w=1600&q=80');"></div>
  </div>
  <div class="hero-content">
    <h1><i class="fa-solid fa-train"></i> Welcome to Railway Management System</h1>
    <div class="typing-container">
      <span class="typing-text"></span>
    </div>
  </div>
</div>


<!-- Main Options -->

<div class="dashboard-grid">

<a href="auth.php" class="card">

<div class="card-body">

<div class="card-icon text-info">
<i class="fa-solid fa-user"></i>
</div>

<h4>Login / Register</h4>

<p>Create an account or login to manage bookings.</p>

</div>

</a>


<a href="view_trains.php" class="card">

<div class="card-body">

<div class="card-icon text-warning">
<i class="fa-solid fa-train-subway"></i>
</div>

<h4>View Trains</h4>

<p>Explore all available trains and schedules.</p>

</div>

</a>

</div>



<!-- Features -->

<h3 class="features-title">Why Choose Our Railway System?</h3>

<div class="dashboard-grid">

<div class="card">

<div class="card-body">

<div class="card-icon text-success">
<i class="fa-solid fa-ticket"></i>
</div>

<h4>Easy Booking</h4>

<p>Book train tickets quickly in just a few steps.</p>

</div>

</div>


<div class="card">

<div class="card-body">

<div class="card-icon text-danger">
<i class="fa-solid fa-lock"></i>
</div>

<h4>Secure System</h4>

<p>Your personal data and bookings remain secure.</p>

</div>

</div>


<div class="card">

<div class="card-body">

<div class="card-icon text-primary">
<i class="fa-solid fa-bolt"></i>
</div>

<h4>Instant Confirmation</h4>

<p>Receive booking confirmation instantly.</p>

</div>

</div>

</div>


<div class="footer">

<p>Railway Management System © 2026</p>

</div>

</div>

<script>
const words = [
  "Book train tickets quickly and securely.",
  "Manage your journeys with ease.",
  "Explore railway schedules dynamically.",
  "Experience the comfort of modern travel."
];
let i = 0;
let timer;

function typingEffect() {
    let word = words[i].split("");
    var loopTyping = function() {
        if (word.length > 0) {
            document.querySelector('.typing-text').innerHTML += word.shift();
        } else {
            setTimeout(deletingEffect, 2500);
            return false;
        }
        timer = setTimeout(loopTyping, 70);
    };
    loopTyping();
}

function deletingEffect() {
    let word = words[i].split("");
    var loopDeleting = function() {
        if (word.length > 0) {
            word.pop();
            document.querySelector('.typing-text').innerHTML = word.join("");
        } else {
            if (words.length > (i + 1)) {
                i++;
            } else {
                i = 0;
            }
            setTimeout(typingEffect, 500);
            return false;
        }
        timer = setTimeout(loopDeleting, 30);
    };
    loopDeleting();
}
if(document.querySelector('.typing-text')){
    typingEffect();
}
</script>

</body>
</html>
