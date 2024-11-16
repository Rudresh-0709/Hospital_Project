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
    $flag = 0;
    try{
    $stmt = $conn->prepare("UPDATE admit set date_out = ? , status = 0 where admit_id = ?");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("si", $dt, $aid);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    if($stmt->execute()){
        echo "<div class=\"valid\">Discharge Succesfull ! </div>";
        $flag = 1;
    }

}
catch(mysqli_sql_exception $e){
    echo "<div class=\"invalid\"> Error!  $e </div>";
    echo $e->getCode();    // helps to get error code so i can handle them accordingly

}

if($flag){
    try{
        $stmt1 = $conn->prepare("select bed_id from admit where admit_id = ?");
        $stmt1->bind_param("i",$aid);
        $stmt1->execute();
        $result = $stmt1->get_result()->fetch_assoc();
        $bid = $result["bed_id"];

        $stmt2 = $conn->prepare("UPDATE beds set status = 1 where bed_id = ?");
        // prepare statement used to insert the form data into the user_details table

        $stmt2->bind_param("s", $bid);
        //  This ensures that the data is properly escaped, preventing SQL injection attacks.
        // "ssss" indicates all parameters are strings.
        if($stmt2->execute()){
            echo "<div class=\"valid\">Bed khali</div>";
        }
    }
    catch(mysqli_sql_exception $e){
        echo "<div class=\"invalid\"> Error!  $e </div>";
        echo $e->getCode();    // helps to get error code so i can handle them accordingly    
    }

    $stmt->close();
    $conn->close();
}
?>
