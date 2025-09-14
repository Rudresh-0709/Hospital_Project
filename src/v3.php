<?php
    require('connection.php');
    if($conn->connect_error){ 
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $aid = $_POST['aid'];
    }
// code fetching badge_id from admit_id
try{
    $stmt = $conn->prepare("SELECT badge_id as bid from badges where admit_id = ?");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("i", $aid);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    $stmt->execute();

    $arr = [];
    $result2 = $stmt->get_result();
    while ($row = $result2->fetch_assoc()) {
        $arr[] = $row["bid"];
    }
    if (count($arr) > 0) {
        // had to club all badges in one array 
        echo json_encode(["flag" => true, "message" => $arr]);
        // only single response can be sent by server(php) to ajax
        // or json format is violated
    } else {
        echo json_encode(["flag" => false, "message" => "No badges found for the given Admit ID. $aid"]);
    }
    $stmt->close();
    $conn->close();
}
catch(mysqli_sql_exception $e){
    echo json_encode(["flag" => false, "message" => "Error ! $e ||| $e->getCode()"]);
    // echo "<div class=\"invalid\"> Error!  $e </div>";
    // echo $e->getCode();    // helps to get error code so i can handle them accordingly
}
?>
