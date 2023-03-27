<?php
session_start();
if (!isset($_GET['from_date']) || !isset($_GET['to_date']) || !isset($_GET['email'])) {
    $SESSION['email_msg'] = 'Email not found';
    header('location:../../invoice_report.php');
}
$_POST['from_date'] = $_GET['from_date'];
$_POST['to_date'] = $_GET['to_date'];
include '../../../queries/invoice.php';
include '../../../queries/customer.php';
$invoice = new Invoice();
$customer = new Customer();
$data = $invoice->getInvoice_fromDate($_POST);
$data1 = $invoice->totalAmt_invoiceCount($_POST);
$company = $invoice->companyInfo();

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

    $pdf->Cell(95, 5, "Invoice Report:", "T", 0, 'L');
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(95, 5, "Number of Invoices:" . $data1['row'], "T", 1, 'R');
    $pdf->Cell(95, 5, "Period:" . $_GET['from_date'] . " to " . $_GET['to_date'], "B", 0, 'L');
    $pdf->Cell(95, 5, "Total Amount:" . $data1['total'], "B", 1, 'R');
    //  $pdf->Cell(0, 10, "", 'T', 1, 'C');


    $pdf->Cell(0, 2, "", 0, 1);
    //index-title

    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(10, 5, "S.N.", 1, 0, 'C');
    $pdf->Cell(24, 5, "Create Date", 1, 0, 'C');
    $pdf->Cell(30, 5, "Recorded By", 1, 0, 'C');
    $pdf->Cell(23, 5, "Invoice No.", 1, 0, 'C');
    $pdf->Cell(36, 5, "Customer", 1, 0, 'C');
    $pdf->Cell(28, 5, "Status", 1, 0, 'C');
    $pdf->Cell(11, 5, "Tax", 1, 0, 'C');
    $pdf->Cell(32, 5, "Invoice Total", 1, 1, 'C');
    $pdf->SetFont("Times", "", 10);
    $count = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $customerDetail = $customer->getCustomersbyId($row['order_receiver_id']);
        $salesPerson = $invoice->getSalesPersonById($row['recorded_by']);
        $invoiceDate = date("d/M/Y", strtotime($row["order_date"]));

        if ($row["order_amount_paid"] == $row["order_total_after_tax"]) {
            $status = "Paid";
        } elseif ($row["order_amount_paid"] == 0) {
            $status = "Not Paid";
        } elseif ($row["order_amount_paid"] < $row["order_total_after_tax"]) {
            $status = "Partially Paid";
        }
        if ($row["order_tax_per"] > 0) {
            $tax = $row["order_tax_per"] . " %";
        } else {
            $tax = $row["order_tax_per"];
        }
        $count++;
        $pdf->Cell(10, 5, $count, 1, 0, 'L');
        $pdf->Cell(24, 5, $invoiceDate, 1, 0, 'L');
        $pdf->Cell(30, 5, $salesPerson['username'], 1, 0, 'L');
        $pdf->Cell(23, 5, $row['order_id'], 1, 0, 'C');
        $pdf->Cell(36, 5, $customerDetail['first_name'] . " " . $customerDetail['last_name'], 1, 0, 'L');
        $pdf->Cell(28, 5, $status, 1, 0, 'C');
        $pdf->Cell(11, 5, $tax, 1, 0, 'L');
        $pdf->Cell(32, 5, $row['order_total_after_tax'], 1, 1, 'L');
    }
    $attachment = $pdf->Output('Invoice report.pdf', 'S');
    ob_end_flush();


    //Attachments
    $mail->addStringAttachment($attachment, 'Invoice report.pdf');
    //Add attachments


    //Content
    $body = 'Invoice report';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Invoice Report';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    if ($mail->Send()) {
        $_SESSION['email_msg'] = 'Report Sent';

?> <script>
            location.replace('../../invoice_report.php');
            //alert('Message has been sent');
        </script>
    <?php
    }
} catch (Exception $e) {
    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    ?> <script>
        location.replace('../../invoice_report.php');
        //alert('Message has been sent');
    </script><?php
            }
