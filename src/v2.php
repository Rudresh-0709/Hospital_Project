<!-- !! TRY CATCH IMPORTANCE !! -->
 <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
  ... does not go to else block where i was handling error 
  This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
  Thus it helps to not terminate the code and just display the error-->

  <?php

    require('connection.php');
    date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $aid = $_POST['admit'];
        $bid = $_POST['badge_select'];
        $currentDateTime = new DateTime();
        $dt = $currentDateTime->format("Y-m-d");
        $tt = $currentDateTime->format("H:i:s");
    }

    try{
    $stmt = $conn->prepare("INSERT into visits (date, time, admit_id, badge_id) values(?,?,?,?)");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("ssii", $dt,$tt, $aid, $bid);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    if($stmt->execute()){
        echo "<div class=\"valid\">Entry Succesfull ! </div>";
    }

    $stmt->close();
    $conn->close();
}
catch(mysqli_sql_exception $e){
    echo "<div class=\"invalid\"> Error!  $e </div>";
    echo $e->getCode();    // helps to get error code so i can handle them accordingly

}
?>