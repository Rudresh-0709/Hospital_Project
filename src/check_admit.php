<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"  />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ruslan+Display&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        html{
            font-family: "Montserrat Alternates", sans-serif;
        }
        #message{
            font-size: 1.4rem;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;

            max-width: 50%;
            height: 30vh;

            border: 7px solid #00800010;
            border-radius: 10px;
            background-color: #faff004c;

            margin: auto;
            #valid{
                background-color: #07ed07;
            }
            #invalid{
                background-color: #ff0000;
            }
            #valid, #invalid{
                font-size: x-large;
                color: #ffffff ;
                border: 2px solid transparent;
                border-radius: 9px;
                padding: 46px;
            }
            #content-box{
                display:flex;
                flex-direction:column;
                gap:20px;
            }
            #visit_msg{
                display:none;
            }
        }
        #visit_msg_main{
                max-width: 50%;
                margin: auto;
                text-align: center;
                margin-top: 8vh;
                font-size: 1.3rem;
        }
    </style>
</head>

<body>
    <div id="message">
    <?php
        require('connection.php');

        $aid = $_GET['admit_id'];  // ???? why GET
        $bid = $_GET['badge_id'];



        $stmt = $conn->prepare("SELECT a.admit_id, a.status, p.name, p.patient_id, a.bed_id FROM admit a join patient p on p.patient_id = a.patient_id and a.admit_id = ?");
        $stmt->bind_param("i", $aid);

        try{
            if($stmt->execute()){
                $result = $stmt->get_result()->fetch_assoc();

                echo "<div id=\"content-box\"><div id=\"content\">Patient name - {$result['name']}</div>";
                echo "<div id=\"content\">Patient ID - {$result['patient_id']}</div>";
                echo "<div id=\"content\">Admit ID - $aid</div>";
                echo "<div id=\"content\">Bed Allocated - {$result['bed_id']} </div></div>";
                
                if($result['status'] == 1){
                    echo "<div id=\"valid\"> Status Active <i class=\"fa-solid fa-check-circle\"></i></div>";

                    date_default_timezone_set('Asia/Kolkata'); // Set timezone to IST

                    $currentDateTime = new DateTime();
                    $dt = $currentDateTime->format("Y-m-d");
                    $tt = $currentDateTime->format("H:i:s");

                    try{
                        $stmt = $conn->prepare("INSERT into visits (date, time, admit_id, badge_id) values(?,?,?,?)");
                        // prepare statement used to insert the form data into the user_details table

                        $stmt->bind_param("ssii", $dt,$tt, $aid, $bid);
                        //  This ensures that the data is properly escaped, preventing SQL injection attacks.
                        // "ssss" indicates all parameters are strings.
                        if($stmt->execute()){
                            echo "<div id=\"visit_msg\">Visit Entry Succesfull for badge $bid !</div>";

                        }

                        $stmt->close();
                        $conn->close();
                    }
                    catch(mysqli_sql_exception $e){
                    echo "<div id=\"visit_msg\"> Error!  $e </div>";
                    echo $e->getCode();    // helps to get error code so i can handle them accordingly

                    }
                }
                else{
                    echo "<div id=\"invalid\"> Status Inactive <i class=\"fa fa-times-circle\"></i></div>";
                }
            }
            else{
                echo "<div id=\"invalid\"> Admit ID invlaid </div>";
            }
        }
        catch(Exception $e){
            echo "EXCEPTION aaya -----     {$e->get_message()}";
            echo $e->getCode();
        }
    ?>
    </div>
    <div id="visit_msg_main">
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#visit_msg_main').text("Please wait");
        setTimeout(function() {
            $('#visit_msg_main').text($('#visit_msg').html());
        }, 2000); // Delay execution by 5000ms (5 seconds)
    });

</script>

</html>