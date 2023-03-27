<?php
session_start();
include '../../../queries/invoice.php';
include '../../../queries/customer.php';
$invoice = new Invoice();
$customer = new Customer();
$customerDetail = $customer->getCustomersbyId($_GET['cust_id']);
$row = mysqli_num_rows($invoice->getCustomerInvoice($_GET['cust_id']));
$data = $invoice->customerTotalSum($_GET['cust_id']);

$total = $data['total'];
$due = $data['due'];


$company = $invoice->companyInfo();
$data1 = $invoice->getCustomerInvoicePay($_GET['cust_id']);
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

$pdf->Cell(95, 5, "Invoice Report of: "  . $customerDetail['first_name'] . " " . $customerDetail['last_name'], "T", 0, 'L');
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(95, 5, "Total Invoice Amount:" . $total, "T", 1, 'R');
$pdf->Cell(95, 5, "No. of Invoices:" . $row, "B", 0, 'L');
$pdf->Cell(95, 5, "Total Due Amount:" . $due, "B", 1, 'R');
//  $pdf->Cell(0, 10, "", 'T', 1, 'C');


$pdf->Cell(0, 2, "", 0, 1);
//index-title

$pdf->SetFont("Times", "B", 10);
$pdf->Cell(15, 5, "S.N.", 1, 0, 'L');
$pdf->Cell(34, 5, "Issued Date", 1, 0, 'L');
$pdf->Cell(34, 5, "Due Date", 1, 0, 'L');
$pdf->Cell(26, 5, "Invoice No.", 1, 0, 'L');
$pdf->Cell(40, 5, "Invoice Total", 1, 0, 'L');
$pdf->Cell(40, 5, "Due Amount", 1, 1, 'L');

$pdf->SetFont("Times", "", 10);
$count = 0;
while ($result = mysqli_fetch_assoc($data1)) {
    $invoiceDate = date("d/M/Y", strtotime($result["order_date"]));
    $dueDate = date("d/M/Y", strtotime($result["due_date"]));

    $due = $result["order_total_amount_due"];
    if ($due == 0) {
        $due = 'Paid';
    }
    $count++;
    $pdf->Cell(15, 5, $count, 1, 0, 'C');
    $pdf->Cell(34, 5, $invoiceDate, 1, 0, 'C');
    $pdf->Cell(34, 5, $dueDate, 1, 0, 'C');
    $pdf->Cell(26, 5, $result['order_id'], 1, 0, 'C');
    $pdf->Cell(40, 5, $result['order_total_after_tax'], 1, 0, 'C');
    $pdf->Cell(40, 5, $due, 1, 1, 'C');
}
$pdf->Output();
//$pdf->Output('D','item_list.pdf',true);
ob_end_flush();
