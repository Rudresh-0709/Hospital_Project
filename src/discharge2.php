<?php
require('connection.php');
date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

if ($conn->connect_error) {
    die("Connection Failed : " . $conn->connect_error);
} else {
    $aid = $_POST['admit'];
    $currentDateTime = new DateTime();
    $dt = $currentDateTime->format("Y-m-d");
}

$flag = 0;

try {
    $stmt = $conn->prepare("UPDATE admit SET date_out = ?, status = 0 WHERE admit_id = ?");
    $stmt->bind_param("si", $dt, $aid);

    if ($stmt->execute()) {
        echo "<div class=\"valid\">Discharge Successful ! </div>";
        $flag = 1;
    }
} catch (mysqli_sql_exception $e) {
    echo "<div class=\"invalid\"> Error! $e </div>";
    echo $e->getCode(); // helps to get error code so I can handle them accordingly
}

if ($flag) {
    try {
        $stmt1 = $conn->prepare("SELECT bed_id FROM admit WHERE admit_id = ?");
        $stmt1->bind_param("i", $aid);
        $stmt1->execute();
        $result = $stmt1->get_result()->fetch_assoc();

        if ($result && isset($result["bed_id"])) {
            $bid = $result["bed_id"];

            $stmt2 = $conn->prepare("UPDATE beds SET status = 1 WHERE bed_id = ?");
            $stmt2->bind_param("i", $bid); // bed_id is integer
            $stmt2->execute();
        } 
        // else {
        //     echo "<div class=\"invalid\"> No bed found for admit_id $aid </div>";
        // }
    } catch (mysqli_sql_exception $e) {
        echo "<div class=\"invalid\"> Error in discharge 2! $e </div>";
        echo $e->getCode();
    }

    $stmt->close();
    $conn->close();
}
?>
