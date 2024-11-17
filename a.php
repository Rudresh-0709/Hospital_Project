<!-- !! TRY CATCH IMPORTANCE !! -->
 <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
  ... does not go to else block where i was handling error 
  This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
  Thus it helps to not terminate the code and just display the error-->

<?php
    require('connection.php');
    $flag = 0;
    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pid = $_POST['patient_id'];
        $date_in = $_POST['date_in'];
        $patient_id = $_POST['patient_id']; // Retrieve the patient_id from the query string
    }

    $flag1 = 0;
    $already = $conn->prepare("SELECT admit_id from admit where patient_id = ? and status = 1");    //check if patient is already admitted currently or not
    $already->bind_param("i", $pid);
    $already->execute();
    $res = $already->get_result();
    if($res->num_rows > 0){
        $flag1 = 1;     // i.e patient already admitted currently
        echo "<div class=\"invalid\">";
        echo "<div>Patient already admitted <br> Please Checkout first.</div>";
    }

    if($flag1 == 0){
            try{
            $stm = $conn->prepare("SELECT bed_id from beds where status = 1 LIMIT 1"); // fetch bed that is available;
            if($stm->execute()){
                $res = $stm->get_result()->fetch_assoc();
                $bed = $res['bed_id'];
            }
        
            $stmt = $conn->prepare("INSERT INTO admit (patient_id, date_in, bed_id) values (?,?,?)");
            // prepare statement used to insert the form data into the user_details table
        
            $stmt->bind_param("ssi", $pid, $date_in, $bed);
            //  This ensures that the data is properly escaped, preventing SQL injection attacks.
            // "ssss" indicates all parameters are strings.
        
            if($stmt->execute()){
                //  It inserts the provided user data into the database,
                //  executing the SQL query with the actual values instead of the placeholders(?).
                echo "<div class=\"valid\">";
                echo "<div> Entry Succesfull </div>";
                echo "<div class=\"valid\">";
                echo "Bed Allocated - $bed</div>";
        
                $stm1 = $conn->prepare("UPDATE beds set status = 0 where bed_id = ?");
                $stm1->bind_param("i", $bed);
                $stm1->execute();
                
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
                echo "<a href=\"patient_page.html\"><button >Register</button></a>";
            }
            else{
                // for generic errors
                echo "<div class=\"invalid\">Error in admitting ! $e</div>";
            }
        }
        
        
        // code to allocate 3 visiting badges to the admitted patient
        // once admission succesfull
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
    }
