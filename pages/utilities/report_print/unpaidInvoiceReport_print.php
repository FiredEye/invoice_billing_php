<?php
session_start();
include '../../../queries/invoice.php';
include '../../../queries/customer.php';
$invoice = new Invoice();
$customer = new Customer();
$today_date = date('Y-m-d');
$value = $invoice->getUnpaidAmtRow();
$invoiceList = $invoice->getInvoiceList();
$company = $invoice->companyInfo();
ob_start();
require("../fpdf/fpdf.php");
if ($value[0]['row'] <= 14) {
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

$pdf->Cell(95, 5, "Unpaid Invoice Report:", "T", 0, 'L');
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(95, 5, "Number of Unpaid Invoices:" . $value[0]['row'], "T", 1, 'R');
$pdf->Cell(95, 5, "Unpaid Accounts as at:" . $today_date, "B", 0, 'L');
$pdf->Cell(95, 5, "Total Amount:" . $value[0]['total'], "B", 1, 'R');
//  $pdf->Cell(0, 10, "", 'T', 1, 'C');


$pdf->Cell(0, 2, "", 0, 1);
//index-title

$pdf->SetFont("Times", "B", 10);
$pdf->Cell(10, 5, "S.N.", 1, 0, 'C');
$pdf->Cell(34, 5, "Customer", 1, 0, 'C');
$pdf->Cell(28, 5, "Balance Due", 1, 0, 'C');
$pdf->Cell(23, 5, "Due Date", 1, 0, 'C');
$pdf->Cell(18, 5, "Days O/D", 1, 0, 'C');
$pdf->Cell(20, 5, "Invoice No.", 1, 0, 'C');
$pdf->Cell(34, 5, "Recorded By", 1, 0, 'C');
$pdf->Cell(26, 5, "Invoice Total", 1, 1, 'C');
$pdf->SetFont("Times", "", 10);
$count = 0;
foreach ($invoiceList as $invoiceDetails) {
    date_default_timezone_set('Asia/Kathmandu');
    $invoiceDate = date("d/M/Y", strtotime($invoiceDetails["order_date"]));
    $date = date('Y/m/d');
    $dueDate = date("d/M/Y", strtotime($invoiceDetails["due_date"]));
    $dueDate1 = strtotime($invoiceDetails["due_date"]);
    $due_day = ((strtotime($date) - $dueDate1) / 60 / 60 / 24);
    if ($due_day < 0) {
        $due_day = 0;
    }
    if ($invoiceDetails["order_total_amount_due"] > 0) {
        $count++;
        $row = $invoice->getSalesPersonById($invoiceDetails["recorded_by"]);
        $customerDetail = $customer->getCustomersbyId($invoiceDetails['order_receiver_id']);
        $pdf->Cell(10, 5, $count, 1, 0, 'L');
        $pdf->Cell(34, 5, $customerDetail['first_name'] . " " . $customerDetail['last_name'], 1, 0, 'L');
        $pdf->Cell(28, 5, $invoiceDetails['order_total_amount_due'], 1, 0, 'L');
        $pdf->Cell(23, 5, $invoiceDetails['due_date'], 1, 0, 'L');
        $pdf->Cell(18, 5, $due_day, 1, 0, 'C');
        $pdf->Cell(20, 5, $invoiceDetails['order_id'], 1, 0, 'L');
        $pdf->Cell(34, 5, $row['username'], 1, 0, 'L');
        $pdf->Cell(26, 5, $invoiceDetails['order_total_after_tax'], 1, 1, 'L');
    }
}
$pdf->Output();
//$pdf->Output('D','item_list.pdf',true);
ob_end_flush();
