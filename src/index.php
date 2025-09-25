<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Eagle+Lake&family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ruslan+Display&display=swap" rel="stylesheet">
    <style>
        html{
            margin:0;
            padding:0;
            height:100%;
            overflow-x: hidden;
        }

        body{
            height:100%;
            width:100%;
            background-color: white;
            display:flex;
            justify-content: center;
            align-items: center;
        }
        .maindiv{
            height:100%;
            width:100%;
            border-radius: 10px;
            display:flex;
            flex-direction: column;
            align-items: center;
        }
        .adiv{
            display:flex;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            gap:3em;
            width:90%;
            z-index: 1;
        }
        h1{
            font-family:"Montserrat Alternates", sans-serif;
            color: rgb(34, 90, 94);
            font-size:40px;
            text-shadow: 2px 2px 1px rgba(0, 131, 46, 0.46);
            z-index: 1;
        }
        a{
            font-family:"Montserrat Alternates", sans-serif;
            margin-bottom: 2em;
            box-shadow:0.05px 0.05px 2px 0.05px rgb(118, 118, 118);
            height:5em;
            width:60%;
            border-radius:10px;
            color:rgb(44, 109, 109);
            text-decoration: none;
            padding:6px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: x-large;
            backdrop-filter: blur(5px);
            background-color: rgba(255, 255, 255, 0.432);
            text-shadow: 0.5px 0.5px 1px rgba(40, 40, 40, 0.522);
        }
        a:hover{
            transform: scale(1.1);
            transition: all 0.5s ease-in;
        }
        img{
            width: 80%; 
            position: absolute;    
        }
.php-output{
    margin-top: 2em;
    font-family:"Montserrat Alternates", sans-serif;
    font-size: 2em; /* bigger */
    color: #fff;
    background: linear-gradient(135deg, #1abc9c, #16a085);
    padding: 1em 2em;
    border-radius: 15px;
    box-shadow: 0 8px 15px rgba(0,0,0,0.3);
    text-align: center;
    z-index: 2;
    animation: fadeIn 1.5s ease-in-out;
}

/* simple fade-in animation */
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(-20px);}
    100% {opacity:1; transform: translateY(0);}
}

    </style>
</head>

<body>
    <div class="maindiv">
        <h1> Admin Panel</h1>
        <div class="adiv">
            <a href="patient.html" > New Patient</a>
            <a href="admit.php" class="patient">Admit</a>
            <!-- <a href="visitor.html" class="visitor">Visitor</a> -->
            <a href="discharge.html">Discharge</a>
        </div>

<div class="php-output">
    <?php
    echo "🏥 Hello from : " . gethostname();
    ?>
</div>

        <img src="web_img/Group of doctors standing at hospital building.jpg" alt="">
    </div>
</body>
</html>
