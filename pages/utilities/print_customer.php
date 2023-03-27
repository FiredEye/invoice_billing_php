<?php
session_start();
include '../../queries/Customer.php';
$customer = new Customer();
include '../../queries/invoice.php';
$invoice = new Invoice();
$company = $invoice->companyInfo();
$customer->checkLoggedIn();
$customersList = $customer->getCustomerlist();
$row = $customer->getNumRows_Customer();
ob_start();
require("fpdf/fpdf.php");
if ($row <= 20) {
    //A5 size
    $pdf = new FPDF('L', 'mm', $size = 'A5');
    $pdf->AddPage();
    $pdf->Rect(5, 5, 200, 138.5, 'D');
} else {
    //A4 size
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->Rect(5, 5, 200, 287, 'D');
}
$pdf->SetFont("Times", "B", 13);

$pdf->Cell(0, 5, $company[0]['name'], 0, 1, 'C');
$pdf->SetFont("Times", "", 10);
$pdf->Cell(0, 5, "Address:" . $company[0]['address'], 0, 1, 'C');
$pdf->Cell(0, 5, "Phone Number:" . $company[0]['number'], 0, 1, 'C');
$pdf->Cell(35, 5, "Customers List:", 'B', 1);
$pdf->Cell(0, 2, "", 0, 1);
//index-title
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(13, 5, "Sr No.", 1, 0, 'L');
$pdf->Cell(17, 5, "C_Id", 1, 0, 'L');
$pdf->Cell(46, 5, "Customer Name", 1, 0, 'L');
$pdf->Cell(39, 5, "Address", 1, 0, 'L');
$pdf->Cell(25, 5, "Phone No.", 1, 0, 'L');
$pdf->Cell(50, 5, "E-mail", 1, 1, 'L');
$pdf->SetFont("Times", "", 10);
$count = 0;
foreach ($customersList as $customerList) {
    $count++;
    $pdf->Cell(13, 5, $count, 1, 0, 'C');
    $pdf->Cell(17, 5, $customerList['id'], 1, 0, 'C');
    $pdf->Cell(46, 5, $customerList['first_name'] . " " . $customerList['last_name'], 1, 0, 'C');
    $pdf->Cell(39, 5, $customerList['address'], 1, 0, 'C');
    $pdf->Cell(25, 5, $customerList['phone_no'], 1, 0, 'C');
    $pdf->Cell(50, 5, $customerList['email'], 1, 1, 'C');
}

$pdf->Output();
ob_end_flush();
