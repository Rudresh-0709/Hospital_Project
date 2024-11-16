<?php
require('connection.php');
    date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $aid = $_POST['admit'];
        $currentDateTime = new DateTime();
        $dt = $currentDateTime->format("Y-m-d");
    }

    try{
    $stmt = $conn->prepare("UPDATE admit set date_out = ?");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("s", $dt);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    if($stmt->execute()){
        echo "<div class=\"valid\">Discharge Succesfull ! </div>";
    }

    $stmt->close();
    $conn->close();
}
catch(mysqli_sql_exception $e){
    echo "<div class=\"invalid\"> Error!  $e </div>";
    echo $e->getCode();    // helps to get error code so i can handle them accordingly

}
?>
