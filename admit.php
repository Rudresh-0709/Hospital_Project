<?php
// Get patient_id from the URL query string
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';    //fetch only if it is set or set to ""
?>
<!-- had to make this page php so as to  -->
<!-- get the patient_id from URL sent by patient.html and autofill patient_id input with that id -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        html {
            height:100vh;
            margin:0;
            padding:0;
        }
        section {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            height: 100%;
        }
        body {
            height: 100%;
            background-image: linear-gradient(to right, #9ce7d3 0%, #abeff7 50%, #63c1d8 100%);
            /* display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center; */
        }
        img{
            height:80%
        }

        .patientform {
            min-height: 30em;
            background-image: url(wave2.png);
            background-position: center;
            background-size: cover;
            height: fit-content;
            width: 29em;
            border-radius: 10px;
            box-shadow: 1px 1px 10px 1px rgba(0, 0, 0, 0.149);
        }

        h2 {
            margin: 1px;
            text-align: center;
            padding: 1em;
            font-family: "Montserrat Alternates", sans-serif;
            color: white;
            font-size: 35px;
            text-shadow: 3px 3px 2px rgba(0, 255, 213, 0.336);
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 20px;
            font-weight: 800;
            font-family: "Poppins";
        }
        .inputtype {
            position: relative;
            margin-bottom:50px;
        }

        label {
            position: absolute;
            top: 10px;
            left: 13px;
            font-weight: 200;
            font-size: smaller;
            color: rgb(72, 72, 72);
        }

        input{
            width: 250px;
            height: 30px;
            margin-top: 0.5em;
            font-family: "Montserrat Alternates", sans-serif;
            border-radius: 7px;
            outline: none;
            border-color: transparent;
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
        }
        input:hover~label,
        input:focus~label,
        input:valid~label {
            transition: all 0.8s ease;
            top: -25px;
            font-size: medium;
            color: white;
            font-weight: 400;
        }
        #submit{
            width:200px;
            margin-bottom: 40px;
            height:2em;
            color:white;
            font-size: 17px;
            border-radius: 20px;
            background-color: rgb(106 197 219);
            box-shadow: 1px 1px 10px 3px rgba(0, 0, 0, 0.2);
            text-shadow: 1px 1px 2px rgb(0, 255, 162);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        #submit:hover {
            background-color: rgb(157 232 214); /* Change color on hover */
            box-shadow:1px 1px 10px 1px rgba(81, 216, 202, 0.615);
        }

        #date_in {
            color: white;
            font-size: 15px;
        }
        a{
            height:2em;
            width:30%;
            border-radius:10px;
            color:white;
            text-decoration: none;
            padding:4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .invalid{
            color:red;
            font-size: larger;
        }
        .valid{
            color:green;
            font-size: larger;
        }
        .labeli{
            width: fit-content;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1em;
        }
        i{
            margin-left:10px;
            padding-top:8px;
        }
        .nav {
            height: 7%;
            width: 50%;
            /* background-image: radial-gradient(#19cbf876 0%, #3eb2ffc1 50%, #007efcd7 100%); */
            background-color: rgba(27 ,73 ,73 , 70%);
            position: fixed;
            bottom: -100%;
            left: 25%;
            display: flex;
            justify-content: space-between;
            border: 0.1px solid rgba(0, 0, 0, 0.149);
            border-top-left-radius: 25px;
            border-top-right-radius: 25px;
            animation: slideUp 1s forwards;
        }

        @keyframes slideUp {
            from {
                bottom: -100%;
                /* Start from below the viewport */
            }

            to {
                bottom: 0;
                /* End at the bottom of the viewport */
            }
        }

        .navlink {
            display: flex;
            width: 30%;
            text-align: center;
            justify-content: center;
            align-items: center;
            gap: 10px
        }
        .navlink:hover {
            transform: translateY(-1px) scale(1.05);
            transition: all 0.2s ease-in;
        }
        a {
            text-decoration: none;
            color: white;
            font-weight: 50;
            font-size: large;
            font-family: "Montserrat Alternates", sans-serif;
        }

        .navlink i {
            margin: 0;
            padding: 0;
            color: white;
            font-size: larger;
            font-weight: 700;
        }

        h4{font-weight: 300;}

        #message {
            display:flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap:25px;
            .invalid {
                color: red;
                font-weight: 700;
                padding: 0 40px;
                text-align: center;
            }
            
            button{
            width:200px;
            margin-bottom: 40px;
            height:2em;
            color:white;
            font-size: 17px;
            border-radius: 20px;
            border-color:transparent;
            background-color: rgb(106 197 219);
            box-shadow: 1px 1px 10px 3px rgba(0, 0, 0, 0.2);
            text-shadow: 1px 1px 2px rgb(0, 255, 162);
            }
            #email_msg{
                text-align: center;
                color:green;
                h4{
                    margin:-12px;
                }
            }
        }
    </style>

</head>

<body>
    <section>
    <div class="patientform">
        <h2>Patient Admission Form</h2>
        
        <form action="a.php" method="POST" id="admitForm">
            <div class="inputtype">
                <div class="labeli">
                    <input type="text" id="patient_id" name="patient_id" value="<?php echo "".$patient_id.""; ?>" required>
                    <!-- ^ put the fetched patient_id from top line into value of patient_input, so that this info can be sent to a.php via form -->
                    <label for="patient_id">Patient ID:</label>
                    <i class="fa-regular fa-id-badge"></i>
                </div>
            </div>

            <div class="inputtype">
                <div class="labeli">
                    <input type="text" id="email_id" name="email_id" value=" " required>
                    <!-- ^ put the fetched patient_id from top line into value of patient_input, so that this info can be sent to a.php via form -->
                    <label for="email_id">Email ID</label>
                    <i class="fa-regular fa-envelope"></i>
                </div>
            </div>

            
            <div class="inputtype">
                <div class="labeli">
                    <input type="date" name="date_in" id="date_in" required>
                    <label for="date_in">Date in :</label>
                    <i class="fa-solid fa-calendar-days"></i>
                </div>

            </div>

            <input type="submit" value="Submit" id="submit">
            <div id="message">
        
            </div>
        </form>
    </div>
    <img src="doctor-giving-treatment-to-corona-positive-woman.png" alt="">
    </section>
    
    <div class="nav">
        <a href="patient.html" class="navlink">
            <i class="fa-solid fa-bed-pulse"></i>
            <h4>patient</h4>
        </a>
        <a href="visitor.html" class="navlink">
            <i class="fa-solid fa-hospital-user"></i>
            <h4>Visitor</h4>
        </a>
        <a href="discharge.html" class="navlink">
            <i class="fa-solid fa-house-medical-circle-check"></i>
            <h4>Discharge</h4>
        </a>
        <a href="index.html" class="navlink">
            <i class="fa-solid fa-house"></i>
            <h4>Home</h4>
        </a>
    </div>

    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            // giving default value as system date to date input
            const today = new Date(); //system date
            date_val = `${today.getFullYear() }-${ today.getMonth() + 1 }-${ today.getDate() }`;
            document.getElementById("date_in").value = date_val;
            var admit = 0;
            var bed = 0;


            // This line means: once the HTML page is fully loaded, the function inside will run
            $('#admitForm').on('submit', function (e) {
                e.preventDefault(); // Prevents the form from submitting normally (which would reload the page)

                $.ajax({
                    url: 'a.php', // The URL where the data will be sent (in this case, 'a.php')
                    type: 'POST', // The method used to send data (POST is typically used for form submissions)
                    data: $(this).serialize(), // Takes all the form data and formats it for sending
                    success: function (response) {
                        // This function runs if the request is successful
                        $('#message').html(response); // Shows the server's response message in the div with id 'message'
                        admit = $('#admit_store').text();
                        bed = $('#bed_store').text();

                        // had to take in inside this ajax call as otherwise ... this second ajax call was being made .. before complete executiion of the first (outer one)
                        // which would lead to fetch the admit id from #admit_store before it got its value from first ajax code.
                        $.ajax({
                        url: 'email.php', // The URL where the data will be sent (in this case, 'a.php')
                        type: 'POST', // The method used to send data (POST is typically used for form submissions)
                        data: {
                            aid : admit,
                            bid : bed,
                            pid : $('#patient_id').val(),
                            email_id : $('#email_id').val()
                        },
                        success: function (response1) {
                            console.log("Ye response aya", response1);
                            var final = JSON.parse(response1);
                            // This function runs if the request is successful
                            if(final.flag){
                                $('#message').append(final.cont); // Shows the server's response message in the div with id 'message'
                                console.log(final.cont);
                            }
                            else{
                                $('#message').append(final.cont); // Shows the server's response message in the div with id 'message'
                                console.log(final.cont);
                            }
                        },
                        error: function () {
                            // This function runs if there’s an error with the request
                            $('#message').html("Error: Could not process the form submission."); // Shows an error message
                            console.log("AJAX Error bro");
                        }
                    });
                    },
                    error: function () {
                        // This function runs if there’s an error with the request
                        $('#message').html("Error: Could not process the form submission."); // Shows an error message
                    }
                });
            });
        });
    </script>
</body>

</html>