<!-- !! TRY CATCH IMPORTANCE !! -->
 <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
  ... does not go to else block where i was handling error 
  This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
  Thus it helps to not terminate the code and just display the error-->

<?php
    $servername = "localhost";
    $username = "root";
    $password = "maria";
    $dbname = "hospital";

    $flag = 0;    // admission not yet succesfull

    // create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // mysqli is the MySQL Improved extension, which provides an interface for interacting with MySQL databases in PHP.
    // connecting to the database, and performing other database operations.

    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pid = $_POST['patient_id'];
        $date_in = $_POST['date_in'];
    }


    try{
    $stmt = $conn->prepare("INSERT INTO admit (patient_id, date_in) values (?,?)");
    // prepare statement used to insert the form data into the user_details table

    $stmt->bind_param("ss", $pid, $date_in);
    //  This ensures that the data is properly escaped, preventing SQL injection attacks.
    // "ssss" indicates all parameters are strings.

    if($stmt->execute()){
        //  It inserts the provided user data into the database,
        //  executing the SQL query with the actual values instead of the placeholders(?).
        echo "<div class=\"valid\">";
        echo "<div> Entry Succesfull </div>";
        $flag = 1;    // admission succesfull, now can run badge allocation code
    }
    // else{
    //     echo "Error : " . $stmt->error;
    // }
    // without try catch ... the code didnt even reach here,, so else block didnt make any sense..no use

}
catch(mysqli_sql_exception $e){
    // if foreign key error
    if($e->getCode() == 1452){
        echo "<div class=\"invalid\">No past Patient record found. Kindly register the new patient</div>";
        echo "<a href=\"patient_page.html\"><button style=\"background-color:beige\">Register</button></a>";
    }
    else{
        // for generic errors
        echo "<div class=\"invalid\">Error in admitting !</div>";
    }
}


// code to allocate 3 visiting badges to the admitted patient
if($flag){
    try{
        $stmt = $conn->prepare("SELECT admit_id, status from admit where patient_id = ? and status = 1");
        $stmt->bind_param("i", $pid);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        $aid = $result['admit_id'];
        $stmt->close();

        $temp = 0;
        for($i = 0; $i < 3; $i++){
            $stmt1 = $conn->prepare("INSERT INTO badges (admit_id) values (?)");
            $stmt1->bind_param("i", $aid);

            if($stmt1->execute()){
                $temp = 1;
            }
            else{
                $temp = 0;
                break;
            }
        }
        if($temp == 1){
            //  It inserts the provided user data into the database,
            //  executing the SQL query with the actual values instead of the placeholders(?).
            echo "<div class=\"valid\">";
            echo "3 visiting badges allocated </div>";
        }
        // else{
        //     echo "Error : " . $stmt->error;
        // }
        // without try catch ... the code didnt even reach here,, so else block didnt make any sense..no use

        $stmt1->close();
        $conn->close();
    }
    catch(mysqli_sql_exception $e){
        echo "<div class=\"invalid\">Error in badge allocation! $e</div>";
    }
}
