<?php
// 1️⃣ Connect to database
$db = mysqli_connect("localhost","root","","medicine_tracker") or die("Could not connect to database");

// 2️⃣ Get medicine name from URL
if(isset($_GET['name'])){
    $name = $_GET['name'];

    // 3️⃣ Decrease quantity by 1 (but not below 0)
    $sql = "UPDATE medicine_dashboard SET Quantity = Quantity - 1 WHERE Medicine_Name = '$name' AND Quantity > 0";
    mysqli_query($db, $sql);
}

// 4️⃣ Redirect back to dashboard
header("Location: view_medicine.php");
exit;
?>
