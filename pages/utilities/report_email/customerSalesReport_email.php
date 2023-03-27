<?php
session_start();
if (!isset($_GET['from_date']) || !isset($_GET['to_date']) || !isset($_GET['email'])) {
    $SESSION['email_msg'] = 'Email not found';
    header('location:../../customerSales_report.php');
}
$_POST['from_date'] = $_GET['from_date'];
$_POST['to_date'] = $_GET['to_date'];
include '../../../queries/invoice.php';
include '../../../queries/customer.php';
$invoice = new Invoice();
$customer = new Customer();
$company = $invoice->companyInfo();
$data = $invoice->getCustomerSales_fromDate($_POST);
$row = mysqli_num_rows($data);
$totalPaid = $invoice->totalAmtPaid($_POST);
$total = $totalPaid['total'];
$data1 = ["row" => $row, "total" => $total];

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
    if (mysqli_num_rows($data) <= 14) {
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

    $pdf->Cell(95, 8, "Customer Sales Report:", "T", 0, 'L');
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(95, 8, "Number of Customers:" . $data1['row'], "T", 1, 'R');
    $pdf->Cell(95, 8, "Period:" . $_GET['from_date'] . " to " . $_GET['to_date'], "B", 0, 'L');
    $pdf->Cell(95, 8, "Total Amount Paid:" . $data1['total'], "B", 1, 'R');
    //  $pdf->Cell(0, 10, "", 'T', 1, 'C');


    $pdf->Cell(0, 2, "", 0, 1);
    //index-title

    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(20, 5, "S.N.", 1, 0, 'C');
    $pdf->Cell(80, 5, "Customer", 1, 0, 'C');
    $pdf->Cell(45, 5, "Total Invoice Amount", 1, 0, 'C');
    $pdf->Cell(45, 5, "Total Payment Amount", 1, 1, 'C');
    $pdf->SetFont("Times", "", 10);
    $count = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $customerDetail = $customer->getCustomersbyId($row['order_receiver_id']);
        $count++;
        $pdf->Cell(20, 5, $count, 1, 0, 'L');
        $pdf->Cell(80, 5,  $customerDetail['first_name'] . " " . $customerDetail['last_name'], 1, 0, 'L');
        $pdf->Cell(45, 5, $row['total'], 1, 0, 'C');
        $pdf->Cell(45, 5, $row['paid'], 1, 1, 'C');
    }
    $attachment = $pdf->Output('Customer Sales report.pdf', 'S');
    ob_end_flush();


    //Attachments
    $mail->addStringAttachment($attachment, 'Customer Sales report.pdf');
    //Add attachments


    //Content
    $body = 'Customer Sales report';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Customer Sales Report';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    if ($mail->Send()) {
        $_SESSION['email_msg'] = 'Report Sent';

?> <script>
            location.replace('../../customerSales_report.php');
            //alert('Message has been sent');
        </script>
    <?php
    }
} catch (Exception $e) {
    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    ?> <script>
        location.replace('../../customerSales_report.php');
        //alert('Message has been sent');
    </script><?php
            }
