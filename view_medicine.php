<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Dashboard</title>
    
    <?php/*if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = mysqli_real_escape_string($db, $_GET['search']);
    $sql = "SELECT * FROM medicine_dashboard WHERE Medicine_Name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM medicine_dashboard";
} */?>

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f4f6f7;
            margin: 0;
            padding: 20px;
            background-image: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%);
        }


     .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #E7E8D1;
    color: black;
    padding: 15px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.header h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
}
.header button {
    background-color: #B85042;
    color: #fff;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 8px;
    transition: 0.3s;
}
.header button:hover {
    background-color: #A7BEAE;
    color: white;
}


 
        .summary {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }

        .summary-box {
            background-color: white;
            padding: 15px;
            width: 30%;
            text-align: center;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            font-weight: bold;
        }


        .search-box {
            margin-bottom: 15px;
            text-align: right;
        }

        .search-box input {
            padding: 8px;
            width: 200px;
        }


        table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
    background-color: #4f7c82;
    color: white;
    padding: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

        td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
    background-color: #e0ecee;
    transition: 0.3s;
        }


        .status-normal {
            color: green;
            font-weight: bold;
        }

        .status-low {
            color: red;
            font-weight: bold;
        }

        .status-expiry {
            color: orange;
            font-weight: bold;
        }


        .action-btn {
    padding: 6px 12px;
    border-radius: 6px;
    transition: 0.3s;
}
.action-btn:hover {
    transform: scale(1.05);
}
:root {
    --primary: #4f7c82;
    --secondary: #3b6166;
    --success: #28a745;
    --warning: #ffc107;
    --danger: #dc3545;
}

    </style>
<?php
$db = mysqli_connect("localhost", "root", "", "medicine_tracker") or die("Could not connect to database");

// Summary calculations
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

// Search handling
$search = "";
if(isset($_GET['search']) && !empty($_GET['search'])){
    $search = mysqli_real_escape_string($db, $_GET['search']);
    $sql = "SELECT * FROM medicine_dashboard WHERE Medicine_Name LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM medicine_dashboard";
}

$result = mysqli_query($db, $sql);
?>


</head>

<body>


    <div class="header">
        <h1>Medicine Dashboard</h1>
        <a href="medicineform.html">
            <button>Update Stock</button>
        </a>
    </div>


    <div class="summary">
    <div class="summary-box">Total Medicines: <?php echo $totalMedicines; ?></div>
    <div class="summary-box">Low Stock: <?php echo $lowStock; ?></div>
    <div class="summary-box">Imported Medicines: <?php echo $importedMedicines; ?></div>
</div>


    <!--
    search box implement korte pari nai, Insha"ALlah next time try korbo
<div class="search-box">
    <form method="GET" action="medicine_dashboard.php">
        <input type="text" class="search - mb3" name="search" placeholder="Search medicine" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
</div>
-->



    <table action="view_medicine.php">
        <thead>
            <tr>
                <th>Medicine Name</th>
                <th>Quantity (Strips)</th>
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

$db = mysqli_connect("localhost", "root", "", "medicine_tracker") or die("Could not connect to database");


$sql = "SELECT * FROM medicine_dashboard";
$result = mysqli_query($db, $sql);


while($row = mysqli_fetch_assoc($result)) {

    if ($row['Quantity'] <= $row['Low_Limit']) {
        $statusClass = "status-low";
        $statusText = "Low Stock";
    } elseif (strtotime($row['Expiry_Date']) < strtotime('+1 month')) {
        $statusClass = "status-expiry";
        $statusText = "Expiring Soon";
    } else {
        $statusClass = "status-normal";
        $statusText = "Normal";
    }

 
    echo "<tr>";
    echo "<td>" . $row['Medicine_Name'] . "</td>";
    echo "<td>" . $row['Quantity'] . "</td>";
    echo "<td>" . $row['Low_Limit'] . "</td>";
    echo "<td>" . $row['Country'] . "</td>";
    echo "<td>" . $row['Expiry_Date'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td class='$statusClass'>$statusText</td>";
    echo "<td>
        <a href='take_strip.php?name=" . urlencode($row['Medicine_Name']) . "'>
            <button class='action-btn'>Take 1 Strip</button>
        </a>
      </td>";
    echo "</tr>";
}

mysqli_close($db);
?>

        </tbody>
    </table>

</body>
</html>