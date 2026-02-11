<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Summary counts
$totalMedicines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM medicine_dashboard"))['total'];
$lowStock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS low FROM medicine_dashboard WHERE Quantity <= Low_Limit"))['low'];
$importedMedicines = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS imp FROM medicine_dashboard WHERE Country != 'Bangladesh'"))['imp'];

// Search query
$search = "";
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
    $sql="SELECT * FROM medicine_dashboard WHERE Medicine_Name LIKE '%$search%'";
} else {
    $sql="SELECT * FROM medicine_dashboard";
}
$result=mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medicine Dashboard | Medi-Track</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --primary:#0ea5e9; --primary-dark:#0284c7; --secondary:#0f172a; --accent:#14b8a6;
    --bg-light:#f8fafc; --bg-white:#ffffff; --text-main:#334155; --text-light:#64748b;
    --success-bg:#d1fae5; --success-text:#065f46; --danger-bg:#fee2e2; --danger-text:#991b1b;
    --warning-bg:#fef3c7; --warning-text:#92400e; --radius:12px;
    --shadow-lg:0 10px 15px -3px rgba(0,0,0,0.1);
}

*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Inter',sans-serif; background:linear-gradient(135deg,#f0f9ff,#e0f2fe); color:var(--text-main); min-height:100vh; padding:20px;}
.header{position:relative;text-align:center;padding:40px 20px;border-radius:var(--radius);background:linear-gradient(135deg,var(--primary),var(--primary-dark)); color:white;margin-bottom:40px;overflow:hidden;box-shadow:var(--shadow-lg);}
.header h1{font-family:'Poppins',sans-serif;font-size:2.5rem;font-weight:800;margin-bottom:15px;}
.header .btn-group{display:flex;justify-content:center;gap:15px;flex-wrap:wrap;}
.btn{padding:12px 28px;font-weight:600;font-size:1rem;border-radius:50px;border:none;cursor:pointer;transition:all 0.3s;}
.btn-primary{background-color:var(--bg-white);color:var(--primary-dark);}
.btn-primary:hover{background-color:rgba(255,255,255,0.85);transform:translateY(-2px);}
.btn-outline{background:transparent;border:2px solid white;color:white;}
.btn-outline:hover{background-color:rgba(255,255,255,0.2);}
.blob{position:absolute;border-radius:50%;filter:blur(100px);opacity:0.5;z-index:1;}
.blob-1{width:400px;height:400px;background:#bae6fd;top:-10%;left:-10%;animation:move 20s infinite alternate;}
.blob-2{width:300px;height:300px;background:#99f6e4;bottom:-10%;right:-10%;animation:move 25s infinite alternate-reverse;}
@keyframes move{from{transform:translate(0,0);}to{transform:translate(50px,50px);}}
.summary{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:25px;margin-bottom:40px;}
.summary-box{background:var(--bg-white);padding:30px;border-radius:var(--radius);box-shadow:var(--shadow-lg);text-align:center;transition:transform 0.3s;position:relative;}
.summary-box:hover{transform:translateY(-5px);}
.summary-box span{font-size:2.5rem;font-weight:700;display:block;margin-bottom:8px;}
.table-container{background:var(--bg-white);border-radius:var(--radius);box-shadow:var(--shadow-lg);overflow:hidden;}
table{width:100%;border-collapse:collapse;min-width:800px;}
thead{background:linear-gradient(135deg,var(--primary),var(--primary-dark));color:white;}
th,td{padding:16px;text-align:center;border-bottom:1px solid #f0f0f0;}
tbody tr:hover{background:var(--bg-light);}
.status-normal,.status-low,.status-expiry{padding:6px 14px;border-radius:50px;font-size:0.75rem;font-weight:700;text-transform:uppercase;}
.status-normal{background:var(--success-bg);color:var(--success-text);}
.status-low{background:var(--danger-bg);color:var(--danger-text);}
.status-expiry{background:var(--warning-bg);color:var(--warning-text);}
.action-btn{background:var(--primary);color:white;border:none;padding:6px 14px;border-radius:6px;font-size:0.85rem;font-weight:600;cursor:pointer;transition:0.3s;}
.action-btn:hover{background:var(--primary-dark);transform:translateY(-2px);}
@media(max-width:768px){.summary{grid-template-columns:1fr;}table{min-width:100%;}}
</style>
</head>
<body>

<div class="header">
<h1>Medicine Dashboard</h1>
<div class="btn-group">
    <a href="index.php"><button class="btn btn-outline">Home</button></a>
    <a href="update_stock.php"><button class="btn btn-primary">+ Update Stock</button></a>
    <a href="logout.php"><button class="btn btn-outline" style="background:#ef4444;color:white;">Logout</button></a>
</div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
</div>

<div class="summary">
<div class="summary-box"><span><?= $totalMedicines; ?></span>Total Medicines</div>
<div class="summary-box"><span><?= $lowStock; ?></span>Low Stock Alerts</div>
<div class="summary-box"><span><?= $importedMedicines; ?></span>Imported Items</div>
</div>

<div class="table-container">
<table>
<thead>
<tr>
<th>Medicine Name</th><th>Stock</th><th>Low Limit</th><th>Country</th><th>Expiry Date</th><th>Type</th><th>Status</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php if($result && mysqli_num_rows($result) > 0): ?>
<?php while($row=mysqli_fetch_assoc($result)): 
$statusClass="status-normal"; $statusText="Active";
$expiry=strtotime($row['Expiry_Date']);
if($row['Quantity']<=$row['Low_Limit']){ $statusClass="status-low"; $statusText="Low Stock"; }
elseif($expiry && $expiry<strtotime('+1 month')){ $statusClass="status-expiry"; $statusText="Expiring"; }
?>
<tr>
<td><?= htmlspecialchars($row['Medicine_Name']); ?></td>
<td><?= htmlspecialchars($row['Quantity']); ?></td>
<td><?= htmlspecialchars($row['Low_Limit']); ?></td>
<td><?= htmlspecialchars($row['Country']); ?></td>
<td><?= htmlspecialchars($row['Expiry_Date']); ?></td>
<td><?= htmlspecialchars($row['Type']); ?></td>
<td><span class="<?= $statusClass; ?>"><?= $statusText; ?></span></td>
<td>
<a href="take_strip.php?name=<?= urlencode($row['Medicine_Name']); ?>">
<button class="action-btn">Take 1</button>
</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="8" style="padding:30px;text-align:center;">No medicines found in stock.</td></tr>
<?php endif; ?>
<?php mysqli_close($conn); ?>
</tbody>
</table>
</div>

</body>
</html>
