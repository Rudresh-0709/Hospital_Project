<?php
// Get patient_id from the URL query string
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';
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
            overflow-y: hidden;
            height:100%;
            margin:0;
            padding:0;
        }

        body {
            height: 100%;
            background-image: linear-gradient(to right, #9ce7d3 0%, #abeff7 50%, #63c1d8 100%);
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
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
        label {
            font-weight: 500;
            text-shadow: 2px 2px 2px rgba(0, 255, 204, 0.522);
        }

        input{
            padding:0px 8px;
            height:1.8em;
            margin-top: 0.5em;
            font-family:"Montserrat Alternates", sans-serif;
            border-radius: 7px;
            outline: none;
            border-color: transparent;
            background-color: rgba(0, 0, 0, 0.2);
            color: white;
        }

        #submit{
            width:10em;
            margin-top: 4em;
            margin-bottom: 2em;
            height:3em;
            color:white;
            font-size: 13px;
            color:white;
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
            padding:2px;
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
            padding: 2px;
            width:45%;
            display:flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1em;
        }
        i{
            margin-left:10px;
            padding-top:8px;
        }
    </style>

</head>

<body>
    <div class="patientform">
        <h2>Patient Admission Form</h2>
        
        <form action="a.php" method="POST" id="admitForm">
            <label for="patient_id">Patient ID:</label>
            <div class="labeli">
                <input type="text" id="patient_id" name="patient_id" value="<?php echo "".$patient_id.""; ?>" required>
                <i class="fa-regular fa-id-badge"></i>
            </div>
            
            <label for="date_in">Date in :</label>
            <div class="labeli">
                <input type="date" name="date_in" id="date_in">
                <i class="fa-solid fa-calendar-days"></i>
            </div>

            <input type="submit" value="Submit" id="submit">
        </form>
    </div>

    <div id="message">

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            // giving default value as system date to date input
            const today = new Date(); //system date
            date_val = `${today.getFullYear() }-${ today.getMonth() + 1 }-${ today.getDate() }`;
            document.getElementById("date_in").value = date_val;


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