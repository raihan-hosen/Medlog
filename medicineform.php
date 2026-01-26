<?php
// --- Enable error reporting (for development only) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// --- Database connection ---
// IMPORTANT: Replace the placeholders with your local credentials
// before running the project locally
$db_host = "DB_HOST";       // e.g., "localhost"
$db_user = "DB_USER";       // e.g., "root"
$db_pass = "DB_PASS";       // your MySQL password
$db_name = "DB_NAME";       // e.g., "medicine_dashboard"

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name) 
    or die("Could not connect to database. Please set your credentials.");

// --- Form submission ---
if(isset($_POST['Add'])){
    // Get form data
    $medcinename = mysqli_real_escape_string($db, $_POST['medicine']);
    $quantity    = (int)$_POST['quantity'];
    $lowstock    = (int)$_POST['lowStock'];
    $country     = mysqli_real_escape_string($db, $_POST['country']);
    $expire      = mysqli_real_escape_string($db, $_POST['expiryDate']);
    $type        = mysqli_real_escape_string($db, $_POST['type']);

    // Insert into database
    $sql = "INSERT INTO medicine_dashboard (Medicine_Name, Quantity, Low_Limit, Country, Expiry_Date, Type) 
            VALUES ('$medcinename', '$quantity', '$lowstock', '$country', '$expire', '$type')";

    $data = mysqli_query($db, $sql);

    if($data){
        echo "Stock Updated Successfully";
    } else {
        echo "Failed to Update Stock";
    }

    // Redirect to view page
    header("Location: view_medicine.php");
    exit;
}
?>
