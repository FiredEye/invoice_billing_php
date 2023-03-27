<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/Customer.php';
$invoice = new Invoice();
$customer = new Customer();
$invoice->checkLoggedIn();

if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
    /// echo $_GET['invoice_id'];

    $company = $invoice->companyInfo();
    $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
    $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
    $row = $invoice->getNumRows_orderItem($_GET['invoice_id']);
    $customerDetail = $customer->getCustomersbyId($invoiceValues['order_receiver_id']);
    $salesPerson = $invoice->getSalesPersonById($invoiceValues['recorded_by']);
}
$invoiceDate = date("d/M/Y", strtotime($invoiceValues['order_date']));
$dueDate = date("d/M/Y", strtotime($invoiceValues['due_date']));

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

$pdf->Output();

ob_end_flush();
