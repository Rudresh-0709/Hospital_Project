<?php 

use PHPMailer\PHPMailer\PHPMailer;  // use allows to reference classes
use PHPMailer\PHPMailer\Exception;


$servername = "localhost";
$username = "root";
$password = "maria";
$dbname = "hospital";

require('C:\xampp\htdocs\Hospital\DOCS\fpdf\fpdf.php');  //lightweight PHP library for generating PDF files.
require 'DOCS/PHPMailer/src/PHPMailer.php';  // main file for email sending
require 'DOCS/PHPMailer/src/Exception.php';  // handle exception thrown by PHPmailer
require 'DOCS/PHPMailer/src/SMTP.php';   // functionality for sending emails using an SMTP server



// Dynamic Data
// $admit_id = $_POST['admit'];
// $pname = $_POST['patient_name'];
$aid = $_POST['aid'];
$pid = $_POST['pid'];    
$bid = $_POST['bid'];
$date = date('Y-m-d');
$pname = "Defualt";
$badge_ids = [];

require('connection.php');
if($conn->connect_error){ 
    die("connection Failed : " . $conn->connect_error);
}
else{
    try{
        $stmt = $conn->prepare("Select badge_id from badges where admit_id = ?");
        $stmt->bind_param("i", $aid);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $badge_ids[] = $row['badge_id']; // Add each badge_id to the array
        }

        $stmt1 = $conn->prepare("Select name from patient where patient_id = ?");
        $stmt1->bind_param("i", $pid);
        $stmt1->execute();
        $res = $stmt1->get_result()->fetch_assoc();
        $pname = $res['name'];
    }
    catch(mysqli_sql_exception $me){
        echo json_encode(["flag"=>false, "cont"=>"$me"]);
    }
    finally {
        $conn->close(); // Ensure connection is closed
    }

    // $recipient_email = "chauhanrudresh2005@gmail.com";
    $recipient_email = $_POST['email_id'];
    $recipient_name = $pname;

    // Step 1: Generate the PDF and save it temporarily
    $pdf = new FPDF(); // creates new pdf doc
    $pdf->AddPage();   // adds new page to pdf .. a4 by defualt
    $pdf->SetFont('Arial', 'B', 16);

    // Add PDF content
    // Cell(width, height, text, border, line-break, alignment): Adds a text cell.
    $pdf->Cell(0, 10, 'Patient Visitor e-Badge Details', 0, 1, 'C');  // C -> center  0-> width means stretch to full page   0-> borders    1->ln means next cell on next line
    $pdf->Ln(10);       // line break  ... 10 mm
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 10, 'Patient Name :', 0, 0);     // 0->ln means next cell will come on same line
    $pdf->Cell(50, 10, $pname, 0, 1);
    $pdf->Cell(50, 10, 'Admit ID:', 0, 0);
    $pdf->Cell(50, 10, $aid, 0, 1);
    $pdf->Cell(50, 10, 'Patient ID:', 0, 0);
    $pdf->Cell(50, 10, $pid, 0, 1);
    $pdf->Cell(50, 10, 'Date:', 0, 0);
    $pdf->Cell(50, 10, $date, 0, 1);
    $pdf->Cell(50, 10, 'Bed Allocated:', 0, 0);
    $pdf->Cell(50, 10, $bid, 0, 1);
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Badges:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    foreach ($badge_ids as $index => $bid) {
        $pdf->Cell(10, 10,'.', 0, 0);
        $pdf->Cell(50, 10, $bid, 0, 1);
    }

    // Save the PDF temporarily in the same directory as this file
    $file_path = "{$aid}-temp_badge_details.pdf";
    $pdf->Output('F', $file_path); // 'F': Saves the PDF to a file on the server instead of directly displaying it.
    //Path: The file is saved at the location specified by $file_path.

        
    //Create an instance; passing `true` enables exceptions
    $mail = new  PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication for server
        $mail->Username   = 'anujvmehta99@gmail.com';                     //SMTP username
        $mail->Password   = 'eijritnbwrbxrfcm';                           //SMTP password (google app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('anujvmehta99@gmail.com', 'checkSafe Hospital'); // sender
        $mail->addAddress($recipient_email, $recipient_name); // receiver
        
        //Content
        $mail->isHTML(true);            //Set email format to HTML
        $mail->Subject = "e-Visiting cards @$pname-$pid / checkSafe.Hospital";
        $mail->Body    = "
        Welcome patient $pname. <br>
        Admit ID : $aid <br>
        Your Badges can be accessed from the pdf below.<br> 
        <br><br>
        <b>Note:</b> Visiting hours are 9am to 11am and 4pm to 6pm only. We expect your complete cooperation. <br><br>
        <i><h5>For your own convenience and safety, Please refrain from letting unwanted personnel access visiting cards.</h5></i> <br>
        Thank you.<br>
        checkSafe Hospital, Mumbai (south).
        ";

        $mail->addAttachment($file_path, "Patient_{$pid}_Badge_Details.pdf"); // Attach the PDF and give file name to appear

        $mail->send();
        echo json_encode(["flag"=>true, "cont"=>"<div id=\"email_msg\"><h4>Badge id's have been sent to ".$recipient_email." with admit_id - $aid</h4></div>"]);

        unlink($file_path); // Deletes the PDF file from server  after it has been attached to the email
    } catch (Exception $e) {
        echo json_encode(["flag"=>false, "cont"=>"Message could not be sent. Mailer Error:$mail->ErrorInfo"]);
    }
}

?>