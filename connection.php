
<?php 
    // Template php file for setting up connection with hospital database
    // used in all php files
    // mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT)       // -- helps catch all possible error
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospital";

    // create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // mysqli is the MySQL Improved extension, which provides an interface for interacting with MySQL databases in PHP.
    // connecting to the database, and performing other database operations.
?>