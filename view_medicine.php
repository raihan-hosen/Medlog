<?php
session_start();

// Redirect to login if not logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// --- Database Connection ---
// Use environment variables or a separate config file for sensitive info
$db_host = "localhost";
$db_user = "YOUR_DB_USERNAME";
$db_pass = "YOUR_DB_PASSWORD";
$db_name = "YOUR_DB_NAME";

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name) 
    or die("Could not connect to database");

// --- Summary calculations ---
$totalQuery = "SELECT COUNT(*) as total FROM medicine_dashboard";
$totalResult = mysqli_query($db, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);
$totalMedicines = $totalRow['total'];

$lowQuery = "SELECT COUNT(*) as low FROM medicine_dashboard WHERE Quantity <= Low_Limit";
$lowResult = mysqli_query($db, $lowQuery);
$lowRow = mysqli_fetch_assoc($lowResult);
$lowStock = $lowRow['low'];

$importedQuery = "SELECT COUNT(*) as imp FROM medicine_dashboard WHERE Country != 'Bangladesh'";
$importedResult = mysqli_query($db, $importedQuery);
$importedRow = mysqli_fetch_assoc($importedResult);
$importedMedicines = $importedRow['imp'];

// --- Search handling ---
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($db, $_GET['search']);
    $sql = "SELECT * FROM medicine_dashboard WHERE Medicine_Name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM medicine_dashboard";
}

$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Add your CSS here (same as before) */
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; padding: 30px; }
        /* ... copy all your CSS from previous code ... */
    </style>
</head>
<body>

<div class="header">
    <h1>Medicine Dashboard</h1>
    <div class="header-actions">
        <a href="/home"><button>Home</button></a>
        <a href="/update-stock"><button>+ Update Stock</button></a>
    </div>
</div>

<div class="summary">
    <div class="summary-box">
        <span style="font-size:2.5rem; color:#111827; display:block; margin-bottom:5px;"><?php echo $totalMedicines; ?></span>
        Total Medicines
    </div>
    <div class="summary-box">
        <span style="font-size:2.5rem; color:#ef4444; display:block; margin-bottom:5px;"><?php echo $lowStock; ?></span>
        Low Stock Alerts
    </div>
    <div class="summary-box">
        <span style="font-size:2.5rem; color:#f59e0b; display:block; margin-bottom:5px;"><?php echo $importedMedicines; ?></span>
        Imported Items
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Stock (Strips)</th>
                <th>Low Limit</th>
                <th>Country</th>
                <th>Expiry Date</th>
                <th>Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['Quantity'] <= $row['Low_Limit']) {
                    $statusClass = "status-low";
                    $statusText = "Low Stock";
                } elseif (strtotime($row['Expiry_Date']) < strtotime('+1 month')) {
                    $statusClass = "status-expiry";
                    $statusText = "Expiring";
                } else {
                    $statusClass = "status-normal";
                    $statusText = "Active";
                }

                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Medicine_Name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Low_Limit']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Country']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Expiry_Date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                echo "<td><span class='$statusClass'>$statusText</span></td>";
                echo "<td><a href='take_strip.php?name=" . urlencode($row['Medicine_Name']) . "'><button class='action-btn'>Take 1</button></a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No medicines found in stock.</td></tr>";
        }

        mysqli_close($db);
        ?>
        </tbody>
    </table>
</div>

</body>
</html>
