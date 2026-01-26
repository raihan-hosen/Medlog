<?php
// --- Connect to database ---
// Replace these placeholders with your local credentials when running locally
$db_host = "DB_HOST";       // e.g., "localhost"
$db_user = "DB_USER";       // e.g., "root"
$db_pass = "DB_PASS";       // your MySQL password
$db_name = "DB_NAME";       // e.g., "medicine_dashboard"

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name) 
    or die("Could not connect to database. Please set your credentials.");

// --- Get medicine name from URL ---
if(isset($_GET['name'])){
    $name = mysqli_real_escape_string($db, $_GET['name']);

    // --- Decrease quantity by 1 (but not below 0) ---
    $sql = "UPDATE medicine_dashboard 
            SET Quantity = Quantity - 1 
            WHERE Medicine_Name = '$name' AND Quantity > 0";
    mysqli_query($db, $sql);
}

// --- Redirect back to dashboard ---
header("Location: view_medicine.php");
exit;
?>
