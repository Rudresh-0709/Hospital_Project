<?php 
    require("connection.php");
    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        $pname = $_POST['patient_name'];
        $currentDateTime = new DateTime();
        $dt = $currentDateTime->format("Y-m-d");
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
    
    
        $stmt = $conn->prepare("SELECT admit_id as aid from admit where patient_id = ? and status = 1");  // 1 denotes that patient is currently admitted
        // prepare statement used to insert the form data into the user_details table
    
        $stmt->bind_param("i", $pid);
        //  This ensures that the data is properly escaped, preventing SQL injection attacks.
        // "ssss" indicates all parameters are strings.
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();   //fetch_assoc is used to fetch EACH row as an associative array eg. each row will be like["aid":xxx]
        echo "<span id=\"admit_id\">" .$result['aid']. "</span>";
    }
    catch(mysqli_sql_exception $e){
            echo "<div class=\"invalid\"> Error!  $e </div>";
            echo $e->getCode();    // helps to get error code so i can handle them accordingly
    }
?>