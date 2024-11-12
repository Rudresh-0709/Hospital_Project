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
        //  It inserts the provided user data into the database,
        //  executing the SQL query with the actual values instead of the placeholders(?).
        echo "<div class=\"valid\"> Entry Succesfull </div>";
    }
    else{
    }
    // else{
    //     echo "Error : " . $stmt->error;
    // }
    // without try catch ... the code didnt even reach here,, so else block didnt make any sense..no use

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
