<!-- !! TRY CATCH IMPORTANCE !! -->
 <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
  ... does not go to else block where i was handling error 
  This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
  Thus it helps to not terminate the code and just display the error-->

  <?php

    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)       // -- helps catch all possible error
    $servername = "localhost";
    $username = "root";
    $password = "maria";
    $dbname = "hospital";

    // create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // mysqli is the MySQL Improved extension, which provides an interface for interacting with MySQL databases in PHP.
    // connecting to the database, and performing other database operations.

    if($conn->connect_error){ 
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pid = $_POST['patient_id'];
    }

    try{
    $stmt = $conn->prepare("SELECT admit_id as aid from admit where patient_id = ?");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("i", $pid);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    echo "<span id=\"admit_id\">" .$result['aid']. "</span>";

}
catch(mysqli_sql_exception $e){
    if($e->getCode() == 1062){
        echo "<div class=\"invalid\"> Aadhar Already registered. New patient cannot be made with same aadhar id</div>";
    }
    else{
        echo "<div class=\"invalid\"> Error!  $e </div>";
        echo $e->getCode();    // helps to get error code so i can handle them accordingly
    }
    
}

try{
    $stmt = $conn->prepare("SELECT badge_id as bid from badges where admit_id = ?");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("i", $result['aid']);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    $stmt->execute();
    $result2 = $stmt->get_result();
    while ($row = $result2->fetch_assoc()) {
        echo "<option value=\"".$row['bid']."\">".$row['bid']."</option>";
    }


    $stmt->close();
    $conn->close();
}
catch(mysqli_sql_exception $e){
    if($e->getCode() == 1062){
        echo "<div class=\"invalid\"> Aadhar Already registered. New patient cannot be made with same aadhar id</div>";
    }
    else{
        echo "<div class=\"invalid\"> Error!  $e </div>";
        echo $e->getCode();    // helps to get error code so i can handle them accordingly
    }
    
}
