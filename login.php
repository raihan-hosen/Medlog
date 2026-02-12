<?php


session_start();
require_once "db.php";

// Redirect if already logged in
if (isset($_SESSION['email'])) {
    header("Location: view_medicine.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: view_medicine.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Medi-Track</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<style>
/* ===== Reset & Base ===== */
* {margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
body{background:#f4f7f6;display:flex;justify-content:center;align-items:center;height:100vh;}
a{text-decoration:none;}
button{cursor:pointer;}

/* ===== Card ===== */
.login-card{
    background:#fff;
    padding:50px 40px;
    border-radius:20px;
    width:100%;
    max-width:420px;
    box-shadow:0 20px 40px rgba(0,0,0,0.08),0 8px 16px rgba(0,0,0,0.05);
    border:1px solid #e6ecea;
    transition:0.3s ease;
}
.login-card:hover{transform:translateY(-4px);}

/* ===== Title ===== */
.login-card h2{
    text-align:center;
    font-family:'Poppins',sans-serif;
    font-size:1.9rem;
    font-weight:700;
    margin-bottom:35px;
    color:#1f2937;
}

/* ===== Form Elements ===== */
form div{margin-bottom:22px;}
label{display:block;margin-bottom:8px;font-size:0.9rem;font-weight:600;color:#374151;}
input[type="email"], 
.password-wrapper input[type="password"],
.password-wrapper input[type="text"]{
    width:100%;
    padding:14px 45px 14px 16px;
    border-radius:12px;
    border:2px solid #e5e7eb;
    font-size:0.95rem;
    transition:all 0.25s ease;
    background:#f9fafb;
}
input::placeholder{color:#9ca3af;}
input:focus{outline:none;border-color:#0ea5e9;background:#fff;box-shadow:0 0 0 4px rgba(14,165,233,0.15);}

/* ===== Password Eye ===== */
.password-wrapper{
    position:relative;
}
.toggle-password{
    position:absolute;
    right:12px;
    top:50%;
    transform:translateY(-50%);
    font-size:1.1rem;
    cursor:pointer;
    user-select:none;
}

/* ===== Button ===== */
button{
    width:100%;
    padding:15px;
    background:#0ea5e9;
    color:#fff;
    border:none;
    font-size:1rem;
    font-weight:600;
    border-radius:12px;
    transition:all 0.3s ease;
    letter-spacing:0.5px;
}
button:hover{
    background:#0284c7;
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(14,165,233,0.25);
}

/* ===== Error Message ===== */
.error{
    background:#fde8e8;
    color:#b91c1c;
    padding:12px;
    border-radius:12px;
    font-size:0.85rem;
    text-align:center;
    margin-bottom:20px;
    border:1px solid #f5c2c7;
}

/* ===== Footer ===== */
.footer-text{
    text-align:center;
    font-size:0.75rem;
    color:#6b7280;
    margin-top:28px;
}

/* ===== Responsive ===== */
@media(max-width:480px){
    .login-card{padding:35px 25px;}
}
</style>
</head>

<body>

<div class="login-card">
    <h2>Medi-Track Login</h2>
    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>
        </div>

        <div class="password-wrapper">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <div>
            <button type="submit" name="login">Login</button>
        </div>
    </form>

    <div class="footer-text">&copy; 2025 Medi-Track. All rights reserved.</div>
</div>

<script>
function togglePassword(){
    const password = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");
    if(password.type === "password"){
        password.type = "text";
        icon.textContent = "🙈";
    } else {
        password.type = "password";
        icon.textContent = "👁";
    }
}
</script>

</body>
</html>
