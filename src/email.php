<?php 

// $servername = "localhost";
// $username = "root";
// $password = "maria";
// $dbname = "hospital";

// require('C:\xampp\htdocs\Hospital\DOCS\fpdf\fpdf.php');  //lightweight PHP library for generating PDF files.
// require 'DOCS/PHPMailer/src/PHPMailer.php';  // main file for email sending
// require 'DOCS/PHPMailer/src/Exception.php';  // handle exception thrown by PHPmailer
// require 'DOCS/PHPMailer/src/SMTP.php';   // functionality for sending emails using an SMTP server
// // require 'vendor/autoload.php';
// require 'C:/xampp/htdocs/Hospital/DOCS/phpqrcode-master/qrlib.php'; // Include the library for QR code

$servername = "db";   // because in docker-compose, db service name is "db"
$username = "hospital_user";
$password = "hospital_pass";
$dbname = "hospital";

// Use relative paths inside container
require __DIR__ . '/DOCS/fpdf/fpdf.php';  
require __DIR__ . '/DOCS/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/DOCS/PHPMailer/src/Exception.php';
require __DIR__ . '/DOCS/PHPMailer/src/SMTP.php';
require __DIR__ . '/DOCS/phpqrcode-master/qrlib.php'; 
// __DIR__ is a magic constant in PHP that expands to the directory of the current file (/var/www/html here).
// This makes it portable (works on Windows and inside Docker).


use PHPMailer\PHPMailer\PHPMailer;  // use allows to reference classes
use PHPMailer\PHPMailer\Exception;
// use chillerlan\QRCode\QRCode;
// use chillerlan\QRCode\QROptions;


$aid = $_POST['aid'];
$pid = $_POST['pid'];    
$bid = $_POST['bid'];

// $aid = 10016;
// $pid = 10023;
// $bid = 16;
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

    // $recipient_email = "anujvmehta219@gmail.com";
    $recipient_email = $_POST['email_id'];
    $recipient_name = $pname;

    //Create an instance; passing `true` enables exceptions
    $mail = new  PHPMailer(true);
    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication for server
        $mail->Username   = 'checksafehospital@gmail.com';                     //SMTP username
        $mail->Password   = 'etljzoessodlbhtz';                           //SMTP password (google app password)
        // $mail->Password   = 'ldpqssqltwhqntxp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('checksafehospital@gmail.com', 'checkSafe Hospital'); // sender
        $mail->addAddress($recipient_email, $recipient_name); // receiver
        
        //Content
        $mail->isHTML(true);            //Set email format to HTML
        $mail->Subject = "e-Visiting cards @$pname-$pid / checkSafe.Hospital";
        $mail->Body    = "
        Welcome patient $pname. <br>
        Admit ID : $aid <br>
       
        <b>Note:</b> Visiting hours are 9am to 11am and 4pm to 6pm only. We expect your complete cooperation. <br><br>
        <i><h5>For your own convenience and safety, Please refrain from letting unwanted personnel access visiting cards.</h5></i> <br>
        Thank you.<br>
        checkSafe Hospital, Mumbai (south).
        ";
    
        $qr_status = 0;
        $badge_error = "";
        $tempFiles = [];

        // this loop iterates 3 times and Makes 3 pdfs with 3 different QR(for diff badgeID) and attaches them to email
        // proper error handling done
        foreach ($badge_ids as $index => $baid) {
            try{

                $qrDir = __DIR__ . "/tmp";   // create a tmp folder outside phpqrcode-master
                if (!file_exists($qrDir)) {
                    mkdir($qrDir, 0777, true);
                }

                // Define the path to save the QR code image
                $qrImagePath = $qrDir . "/{$aid}_badge_{$baid}_qr.png";

                // Major mistake i did was put same file_path name for all three iteration (didnt put baid in it)
                $file_path   = $qrDir . "/{$aid}_badge_{$baid}_details.pdf";


                // URL that the QR code will point to
                $qrData = "http://localhost:8080/hospital/check_admit.php?admit_id=$aid&badge_id=$baid";

                // Generate the QR Code
                QRcode::png($qrData, $qrImagePath, QR_ECLEVEL_L, 10);

                if (!file_exists($qrImagePath)) {
                    $qr_status = 0;
                    continue;
                }
                else{
                    $qr_status = 1;     // QR made succesfully for this iteration
                }

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
                $pdf->Cell(0, 10, 'Badge:', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(10, 10,'.', 0, 0);
                $pdf->Cell(50, 10, $baid, 0, 1);

                $pdf->Ln(10);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, "Scan this QR Code for verification and entry of Badge {$baid}:", 0, 1, 'C');
                $pdf->Image($qrImagePath, 80, $pdf->GetY(), 50, 50); // Adjust position and size


                // Save the PDF temporarily in the same directory as this file
                $pdf->Output('F', $file_path); // 'F': Saves the PDF to a file on the server instead of directly displaying it.
                //Path: The file is saved at the location specified by $file_path.
                if (!file_exists($file_path)) {
                    throw new Exception("PDF file could not be created.");
                }
                else{
                    $mail->addAttachment($file_path, "{$pname}_{$baid}_BadgeDetails.pdf"); // Attach the PDF and give file name to appear
                    // unlink($file_path); // Deletes the PDF file from server  after it has been attached to the email
                    // unlink($qrImagePath);
                }

                // Storing qr and pdf of each iteratrion in an array
                // will delete (unlink) after end of loop
                $tempFiles[] = $qrImagePath;
                $tempFiles[] = $file_path;
            }
            catch(Exception $e){
                $badge_error = $e;
            }
        }

        $mail->send();
        if($qr_status == 1){
            echo json_encode(["flag"=>true, "cont"=>"<div id=\"email_msg\"><h4>Badge id's have been sent to ".$recipient_email." with admit_id - $aid</h4></div>", "qr"=>"QR Success"]);
        }
        else{
            echo json_encode(["flag"=>true, "cont"=>"<div id=\"email_msg\"><h4>Badge id's have been sent to ".$recipient_email." with admit_id - $aid</h4><div>QR generation failed</div></div>"]);
        }

        // cleaning of the pdfs and qr codes 
        foreach ($tempFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        
    } catch (Exception $e) {
        echo json_encode(["flag"=>false, "cont"=>"Message could not be sent. Mailer Error: $e"]);
        // $mail->ErrorInfo
    }
}

//  <!-- // Your Badges can be accessed from the pdfs below.<br>
//         // Each PDF is for one Badge (total 3)<br>
//         <br><br> -->
?>


