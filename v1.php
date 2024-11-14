<!-- !! TRY CATCH IMPORTANCE !! -->
 <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
  ... does not go to else block where i was handling error 
  This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
  Thus it helps to not terminate the code and just display the error-->

  <?php
    require('connection.php');
    if($conn->connect_error){ 
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pname = $_POST['patient_name'];
    }

    try{
    //code fetching patient_id from name
    $query = $conn->prepare("select patient_id from patient where name = ?");
    $query->bind_param("s", $pname);
    $query->execute();
    // $pid = $query->get_result();         wrong to get actual value
    $temp = $query->get_result();       // this will give resultSet of type mysqli object
    $temp = $temp->fetch_assoc();       // even if only one expected output...the resultset is one entire ROW...
                                        //thus need to get that row as an assoc array of php ['patient_id' => <value>].
    $pid = $temp['patient_id'];         // thus, need to access the value of patient_id from array.


    $stmt = $conn->prepare("SELECT admit_id as aid from admit where patient_id = ? and status = 1");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("i", $pid);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();   //fetch_assoc is used to fetch EACH row as an associative array eg. each row will be like["aid":xxx]
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

// code fetching badge_id from admit_id
// shouldve gone for another file
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
