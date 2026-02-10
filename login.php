<?php
session_start();
include("db.php"); // make sure this file connects correctly

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    // Fetch user from DB
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($db, $query);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);

        // Check hashed password
        if(password_verify($password, $user['password'])){
            // Password correct, set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            header("Location: view_medicine.php");
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicine Tracker – Login</title>
<style>
:root{
--primary:#3c796a;
--primary-dark:#2a554a;
--primary-light:#e6f2ef;
--primary-fade:rgba(60,121,106,0.1);
--secondary:#1f2937;
--text-body:#4b5563;
--bg-white:#ffffff;
--shadow-card:0 20px 40px rgba(60,121,106,0.15);
--radius:16px;
}

body{font-family:'Inter',sans-serif;background:linear-gradient(180deg,var(--primary-light),#fff);color:var(--text-body);min-height:100vh;display:flex;align-items:center;justify-content:center;}

.container{max-width:1100px;width:100%;display:grid;grid-template-columns:1.1fr 1fr;gap:60px;padding:40px;}

.hero h1{font-size:2.8rem;font-weight:800;color:var(--secondary);margin-bottom:20px;}
.hero p{font-size:1.1rem;margin-bottom:25px;}
.hero ul{list-style:none;}
.hero li{margin-bottom:12px;padding-left:24px;position:relative;}
.hero li::before{content:"✔";position:absolute;left:0;color:var(--primary);font-weight:700;}

.card{background:rgba(255,255,255,0.75);backdrop-filter:blur(14px);border-radius:24px;padding:45px;box-shadow:var(--shadow-card);border:1px solid rgba(255,255,255,0.7);}
.card h2{font-size:2rem;margin-bottom:10px;color:var(--secondary);}
.card p{margin-bottom:30px;}
.form-group{margin-bottom:18px;}
label{display:block;font-weight:600;margin-bottom:6px;color:var(--secondary);}
input{width:100%;padding:14px 16px;border-radius:12px;border:1.5px solid rgba(60,121,106,0.25);font-size:1rem;outline:none;transition:0.25s;}
input:focus{border-color:var(--primary);box-shadow:0 0 0 3px var(--primary-fade);}
button{width:100%;margin-top:10px;padding:15px;border-radius:50px;border:none;background:var(--primary);color:#fff;font-size:1rem;font-weight:700;cursor:pointer;transition:0.3s;box-shadow:0 12px 24px rgba(60,121,106,0.35);}
button:hover{background:var(--primary-dark);transform:translateY(-2px);}
.extra{text-align:center;margin-top:25px;font-size:0.95rem;}
.extra a{color:var(--primary);font-weight:600;text-decoration:none;}
.extra a:hover{text-decoration:underline;}
.error{color:red;margin-bottom:15px;font-weight:600;}
@media(max-width:900px){.container{grid-template-columns:1fr;text-align:center;}.hero ul{display:inline-block;text-align:left;}}
</style>
</head>

<body>
<div class="container">
  <div class="hero">
    <h1>Welcome Back</h1>
    <p>Log in to access your medicine records, dashboards, and system tools securely.</p>
    <ul>
      <li>Secure user authentication</li>
      <li>Fast access to dashboards</li>
      <li>Protected medical data</li>
      <li>Designed for healthcare systems</li>
    </ul>
  </div>

  <div class="card">
    <h2>Login to Your Account</h2>
    <p>Enter your credentials to continue</p>

<form method="post">
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="Enter your email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>
    </div>
    <button type="submit" name="login">Login</button>
</form>

<?php 
if(isset($error)){
    echo "<p style='color:red; text-align:center; margin-top:10px;'>$error</p>";
}
?>

    <div class="extra">
      Don’t have an account? <a href="signup.php">Create one</a>
    </div>
  </div>
</div>
</body>
</html>
