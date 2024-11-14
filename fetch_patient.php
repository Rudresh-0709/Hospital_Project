<?php
//   <!-- !! TRY CATCH IMPORTANCE !! -->
//   <!-- when there was any error occuring from stmt->execute line , the error would itself terminate the code and give its own error msg 
//    ... does not go to else block where i was handling error 
//    This shows the Need for try and catch as it catches such error and provides the steps that the program should execute(handling) 
//    Thus it helps to not terminate the code and just display the error-->
 

    require('connection.php');
    
    if($conn->connect_error){
        die("connection Failed : " . $conn->connect_error);
    }
    else{
        //
    }

    try{
        $query = $conn->prepare("select name from patient");
        $query->execute();  
        $temp = $query->get_result();
        $arr = [];
        // for($i = 0; $i < $temp->num_rows; $i++){
        //     $arr[$i] = $temp->fetch_assoc();
        // }    msqli objects are desgined to be iterated by while loops
        // so for loop wont work properly here

        while($row = $temp->fetch_assoc()){
            $arr[] = $row;
            // each entry will be like [["patient_name":"abc"], ...
        }
        echo json_encode($arr);
    }

    catch(mysqli_sql_exception $e){ 
        // echo $e->getCode();    // helps to get error code so i can handle them accordingly
    }
    $conn->close();
    $query->close();
?>