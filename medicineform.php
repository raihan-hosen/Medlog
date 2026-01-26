<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php
    $db=mysqli_connect("localhost","root","","medicine_tracker") or die("could not connect to database");
    if(isset($_POST['Add'])){
    $medcinename = $_POST['medicine'];
    $quantity = $_POST['quantity'];
    $lowstock = $_POST['lowStock'];
    $country = $_POST['country'];
    $expire = $_POST['expiryDate'];
    $type = $_POST['type'];

    $sql = "INSERT INTO medicine_dashboard (Medicine_Name, Quantity, Low_Limit, Country, Expiry_Date, Type) VALUES ('$medcinename', '$quantity', '$lowstock', '$country', '$expire', '$type')";
    $data= mysqli_query($db, $sql);

    if($data){
        echo "Stock Updated Successfully";
    } else {
        echo "Failed to Update Stock";
    }
    header("Location: view_medicine.php");
    exit;
}
?>