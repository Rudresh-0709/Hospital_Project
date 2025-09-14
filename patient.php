
  <?php
    header('Content-Type: application/json');
//    !! TRY CATCH IMPORTANCE !! 
//    when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
//    ... does not go to else block where i was handling error 
//    This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
//    Thus it helps to not terminate the code and just display the error-->
 

    require('connection.php');
    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pname = $_POST['patient_name'];
        $patient_aadhar = $_POST['patient_aadhar'];
    }

    try{
    $stmt = $conn->prepare("INSERT INTO patient (name, aadhar) values (?,?)");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("ss", $pname, $patient_aadhar);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.

    if($stmt->execute()){
        $patient_id = $conn->insert_id;
        // after running above execute of stmt ... system knows the last incremented value of patient_id
        // insert_id tracks it thus,
        // no need to run these statments.. it gets value directly from the last generated patient_id.

        echo json_encode(["flag" => true, "message" => "admit.php?patient_id=" . $patient_id]);
        // JSON (JavaScript Object Notation) is a lightweight data format
        // JSON is commonly used in web applications to transfer data between the client (your browser) 
        // and the server (your PHP script).

        // json_encode() is the PHP function that converts an associative array "[flag => true,...]" or object 
        // into a valid JSON string(almost like a dictionary in python) "{flag:true, ...}
    }

    $stmt->close();
    $conn->close();
}
catch(mysqli_sql_exception $e){
    if($e->getCode() == 1062){
        echo json_encode(["flag" => false, "message"=>"<div class=\"invalid\"> Aadhar Already registered. New patient cannot be made with same aadhar id</div>"]);
    }
    else{
        echo json_encode(["flag" => false, "message"=>"<div class=\"invalid\"> General Error ! $e </div>"]);
        // echo $e->getCode();    // helps to get error code so i can handle them accordingly
    }
    
}
