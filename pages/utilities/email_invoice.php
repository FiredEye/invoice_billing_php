<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/Customer.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
$customer = new Customer();

if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    //echo $_GET['invoice_id'];

    $company = $invoice->companyInfo();
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
    $row = $invoice->getNumRows_orderItem($_GET['invoice_id']);
    $customerDetail = $customer->getCustomersbyId($invoiceValues['order_receiver_id']);
    $salesPerson = $invoice->getSalesPersonById($invoiceValues['recorded_by']);
}
$invoiceDate = date("d/M/Y", strtotime($invoiceValues['order_date']));
$dueDate = date("d/M/Y", strtotime($invoiceValues['due_date']));
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'phpmailer/vendor/autoload.php';

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
    $mail->addAddress($customerDetail['email']);     //Add a recipient

    /*PDF Format*/
    ob_start();
    require("fpdf/fpdf.php");
    if ($row <= 7) {
        //A5 size
        $pdf = new FPDF('L', 'mm', $size = 'A5');
        $pdf->AddPage();
        // $pdf->Rect(5, 5, 200, 138.5, 'D');
    } else {
        //A4 size
        $pdf = new FPDF();
        $pdf->AddPage();
        // $pdf->Rect(5, 5, 200, 287, 'D');
    }

    $pdf->image("../" . $company[0]['logo'], 10, 0, 30, 30);
    $pdf->SetFont("Times", "B", 13);

    $pdf->Cell(0, 4, "Invoice Statement", 0, 1, 'C');
    $pdf->SetFont("Times", "", 10);
    $pdf->Cell(0, 5, $company[0]['name'], 0, 1, 'C');
    $pdf->Cell(0, 5, "Address:" . $company[0]['address'], 0, 1, 'C');
    $pdf->Cell(0, 5, "Phone Number:" . $company[0]['number'], 0, 1, 'C');
    $pdf->Cell(0, 1, "", 'B', 1, 'C');

    //index-start
    $pdf->SetFont("Times", "", 10);
    //customer-detail
    $pdf->Cell(35, 5, "Customer Name:", 0, 0);
    $pdf->Cell(80, 5, $customerDetail['first_name'] . " " . $customerDetail['last_name'], 0, 0);
    $pdf->Cell(30, 5, "Invoice No:", 0, 0, 'R');
    $pdf->Cell(45, 5, $invoiceValues['order_id'], 0, 1, 'C');
    $pdf->Cell(35, 5, "Billing Address:", 0, 0);
    $pdf->Cell(80, 5, $customerDetail['address'], 0, 0);
    $pdf->Cell(30, 5, "Created Date:", 0, 0, 'R');
    $pdf->Cell(45, 5, $invoiceDate, 0, 1, 'C');
    $pdf->Cell(35, 5, "Phone Number:", 0, 0);
    $pdf->Cell(80, 5, $customerDetail['phone_no'], 0, 0);
    $pdf->Cell(30, 5, "Due Date:", 0, 0, 'R');
    $pdf->Cell(45, 5, $dueDate, 0, 1, 'C');

    //index-title
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(20, 5, "Sr No.", 1, 0, 'L');
    $pdf->Cell(25, 5, "Item Code", 1, 0, 'L');
    $pdf->Cell(65, 5, "Item Name", 1, 0, 'L');
    $pdf->Cell(30, 5, "Price", 1, 0, 'L');
    $pdf->Cell(25, 5, "Quantity", 1, 0, 'L');
    $pdf->Cell(25, 5, "Amount", 1, 1, 'L');
    $pdf->SetFont("Times", "", 10);
    $count = 0;
    foreach ($invoiceItems as $invoiceItem) {
        $count++;
        $pdf->Cell(20, 5, $count, 1, 0, 'C');
        $pdf->Cell(25, 5, $invoiceItem["item_code"], 1, 0, 'C');
        $pdf->Cell(65, 5, $invoiceItem["item_name"], 1, 0, 'C');
        $pdf->Cell(30, 5, $invoiceItem["order_item_price"], 1, 0, 'C');
        $pdf->Cell(25, 5, $invoiceItem["order_item_quantity"], 1, 0, 'C');
        $pdf->Cell(25, 5, $invoiceItem["order_item_final_amount"], 1, 1, 'C');
    }
    $pdf->Cell(125, 5, "", 0, 0);
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(40, 5, "Sub Total: Rs.", 0, 0, 'C');
    $pdf->Cell(25, 5, "Rs." . $invoiceValues['order_total_before_tax'], 0, 1, 'L');
    $pdf->Cell(125, 5, "", 0, 0);
    $pdf->Cell(40, 5, "Tax Rate:", 0, 0, 'C');
    $pdf->Cell(25, 5, $invoiceValues['order_tax_per'], 0, 1, 'L');
    $pdf->SetFont("Times", "", 10);
    $pdf->Cell(125, 5, "", 0, 0);
    $pdf->Cell(40, 5, "Tax Amount: Rs.", 0, 0, 'C');
    $pdf->Cell(25, 5, $invoiceValues['order_total_tax'], 0, 1, 'L');
    $pdf->SetFont("Times", "B", 10);

    $pdf->Cell(50, 5, "", 0, 0, "L");
    $pdf->Cell(10, 5, "", 0, 0, "");
    $pdf->Cell(45, 5, $salesPerson['username'], 0, 0, "C");
    $pdf->Cell(20, 5, "", 0, 0, "");
    $pdf->Cell(40, 5, "Total Amount: Rs.", 0, 0, 'C');
    $pdf->Cell(25, 5, $invoiceValues['order_total_after_tax'], 0, 1, 'L');

    $pdf->Cell(50, 5, "AUTHORISED SIGNATORY", 'T', 0, "L");
    $pdf->Cell(10, 5, "", 0, 0, "");
    $pdf->Cell(45, 5, "PREPARED BY", 'T', 0, "C");
    $pdf->Cell(20, 5, "", 0, 0, "");
    $pdf->Cell(40, 5, "Amount Paid: Rs.", 0, 0, 'C');
    $pdf->Cell(25, 5, $invoiceValues['order_amount_paid'], 0, 1, 'L');
    $pdf->SetFont("Times", "I", 10);
    $pdf->Cell(125, 20, "*fell free to visit our store again", 0, 0);
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(40, 5, "Amount Due: Rs.", 0, 0, 'C');
    $pdf->Cell(25, 5, $invoiceValues['order_total_amount_due'], 0, 1, 'L');

    $attachment = $pdf->Output('invoice detail.pdf', 'S');
    ob_end_flush();


    //Attachments
    $mail->addStringAttachment($attachment, 'invoice detail.pdf');
    //Add attachments


    //Content
    $body = "<p>
            <strong>Invoice " . $invoiceValues['order_id'] . ", Due on " . $dueDate . "<br>
            Dear " . $customerDetail['first_name'] . " " . $customerDetail['last_name'] . ",</strong> 
           <br><br>
            I hope you are well.
            <br><br>
            Please see attached the invoice # " . $invoiceValues['order_id'] . " for [completed project]. The invoice is due by " . $dueDate . ".
            <br><br>
            Please don't hesitate to get in touch if you have any questions or need clarification.
            <br><br>
            Best regards,<br>"
        . $company[0]['email']
        . "</p>";
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Invoice Recipt';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    if ($mail->Send()) {
        $_SESSION['email_msg'] = 'Report Sent';
?> <script>
            location.replace('../invoice_list.php');
            //alert('Message has been sent');
        </script>
    <?php
    }
} catch (Exception $e) {
    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}')</script>";
    ?> <script>
        location.replace('../invoice_list.php');
        //alert('Message has been sent');
    </script><?php
            }
