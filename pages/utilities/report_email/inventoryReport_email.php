<?php
session_start();
include '../../../queries/item.php';
$item = new Item();
include '../../../queries/Invoice.php';
$invoice = new Invoice();
$company = $invoice->companyInfo();
$today_date = date('Y-m-d');
$itemList = $item->getItemList();
$value = $item->getItemAmtQty();
$row = $item->getNumRows_Item();
if ($_GET['email'] == '') {
    $SESSION['email_msg'] = 'Email not found';
    header('location:../../inventory_report.php');
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../phpmailer/vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 1;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;
    //use your email                                    //Enable SMTP authentication1
    $mail->Username   = $company[0]['email'];                     //SMTP username
    // 2-factor authentication password
    $mail->Password   = $company[0]['password'];                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($company[0]['email'], $company[0]['name']);
    $mail->addAddress($_GET['email']);     //Add a recipient

    /*PDF Format*/
    ob_start();
    require("../fpdf/fpdf.php");
    if ($row <= 14) {
        //A5 size
        $pdf = new FPDF('L', 'mm', $size = 'A5');
        $pdf->AddPage();
        // $pdf->Rect(5, 5, 200, 138.5, 'D');
    } else {
        //A4 size
        $pdf = new FPDF();
        $pdf->AddPage();
        //$pdf->Rect(5, 5, 200, 287, 'D');
    }
    $pdf->SetFont("Times", "B", 13);

    $pdf->Cell(0, 5, $company[0]['name'], 0, 1, 'C');
    $pdf->SetFont("Times", "", 10);

    $pdf->Cell(0, 5, "Address:" . $company[0]['address'], 0, 1, 'C');
    $pdf->Cell(0, 5, "Phone Number:" . $company[0]['number'], 0, 1, 'C');
    $pdf->Cell(0, 2, "", 0, 1);
    $pdf->SetFont("Times", "B", 12);

    $pdf->Cell(95, 5, "Inventory Report:", "T", 0, 'L');
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(95, 5, "Total Quantity:" . $value[0]['quantity'], "T", 1, 'R');
    $pdf->Cell(95, 5, "Date: " . $today_date, "B", 0, 'L');
    $pdf->Cell(95, 5, "Total Amount:" . $value[0]['total'], "B", 1, 'R');
    //  $pdf->Cell(0, 10, "", 'T', 1, 'C');


    $pdf->Cell(0, 2, "", 0, 1);
    //index-title

    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(15, 5, "S.N.", 1, 0, 'C');
    $pdf->Cell(30, 5, "Code", 1, 0, 'C');
    $pdf->Cell(50, 5, "Item Name", 1, 0, 'C');
    $pdf->Cell(50, 5, "Quantity", 1, 0, 'C');
    $pdf->Cell(45, 5, "Price", 1, 1, 'C');

    $pdf->SetFont("Times", "", 12);
    $count = 0;
    foreach ($itemList as $itemDetails) {
        $count++;
        $pdf->Cell(15, 5, $count, 1, 0, 'L');
        $pdf->Cell(30, 5, $itemDetails['code'], 1, 0, 'L');
        $pdf->Cell(50, 5, $itemDetails['name'], 1, 0, 'L');
        $pdf->Cell(50, 5, $itemDetails['quantity'], 1, 0, 'L');
        $pdf->Cell(45, 5, $itemDetails['price'], 1, 1, 'L');
    }
    $attachment = $pdf->Output('Inventory report.pdf', 'S');
    ob_end_flush();


    //Attachments
    $mail->addStringAttachment($attachment, 'Inventory report.pdf');
    //Add attachments


    //Content
    $body = 'Inventory report';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Inventory Report';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    if ($mail->Send()) {
        $_SESSION['email_msg'] = 'Report Sent';

?> <script>
            location.replace('../../inventory_report.php');
            //alert('Message has been sent');
        </script>
    <?php
    }
} catch (Exception $e) {
    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    ?> <script>
        location.replace('../../inventory_report.php');
        //alert('Message has been sent');
    </script><?php
            }
