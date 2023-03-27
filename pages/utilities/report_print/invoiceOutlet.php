<?php
session_start();
include '../../../queries/invoice.php';
$invoice = new Invoice();

$company = $invoice->companyInfo();

ob_start();
require("../fpdf/fpdf.php");

//A5 size
$pdf = new FPDF('L', 'mm', $size = 'A5');
$pdf->AddPage();
// $pdf->Rect(5, 5, 200, 138.5, 'D');
$pdf->image("../../" . $company[0]['logo'], 10, 0, 30, 30);
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
$pdf->Cell(80, 5, 'Sonu Gupta', 0, 0);
$pdf->Cell(30, 5, "Invoice No:", 0, 0, 'R');
$pdf->Cell(45, 5, '207', 0, 1, 'C');
$pdf->Cell(35, 5, "Billing Address:", 0, 0);
$pdf->Cell(80, 5, 'Nagarjun', 0, 0);
$pdf->Cell(30, 5, "Created Date:", 0, 0, 'R');
$pdf->Cell(45, 5, '7/5/2020', 0, 1, 'C');
$pdf->Cell(35, 5, "Phone Number:", 0, 0);
$pdf->Cell(80, 5, '9879879870', 0, 0);
$pdf->Cell(30, 5, "Due Date:", 0, 0, 'R');
$pdf->Cell(45, 5, '24/8/2020', 0, 1, 'C');

//index-title
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(20, 5, "Sr No.", 1, 0, 'L');
$pdf->Cell(25, 5, "Item Code", 1, 0, 'L');
$pdf->Cell(65, 5, "Item Name", 1, 0, 'L');
$pdf->Cell(30, 5, "Price", 1, 0, 'L');
$pdf->Cell(25, 5, "Quantity", 1, 0, 'L');
$pdf->Cell(25, 5, "Amount", 1, 1, 'L');
$pdf->SetFont("Times", "", 10);

$pdf->Cell(20, 5, '1', 1, 0, 'C');
$pdf->Cell(25, 5, '103', 1, 0, 'C');
$pdf->Cell(65, 5, 'candle', 1, 0, 'C');
$pdf->Cell(30, 5, '500', 1, 0, 'C');
$pdf->Cell(25, 5, '12', 1, 0, 'C');
$pdf->Cell(25, 5, '6000', 1, 1, 'C');

$pdf->Cell(20, 5, '2', 1, 0, 'C');
$pdf->Cell(25, 5, '104', 1, 0, 'C');
$pdf->Cell(65, 5, 'magnet', 1, 0, 'C');
$pdf->Cell(30, 5, '220', 1, 0, 'C');
$pdf->Cell(25, 5, '5', 1, 0, 'C');
$pdf->Cell(25, 5, '1100', 1, 1, 'C');

$pdf->Cell(20, 5, '3', 1, 0, 'C');
$pdf->Cell(25, 5, '105', 1, 0, 'C');
$pdf->Cell(65, 5, 'large book', 1, 0, 'C');
$pdf->Cell(30, 5, '900', 1, 0, 'C');
$pdf->Cell(25, 5, '2', 1, 0, 'C');
$pdf->Cell(25, 5, '1800', 1, 1, 'C');

$pdf->Cell(20, 5, '4', 1, 0, 'C');
$pdf->Cell(25, 5, '111', 1, 0, 'C');
$pdf->Cell(65, 5, 'cup', 1, 0, 'C');
$pdf->Cell(30, 5, '110', 1, 0, 'C');
$pdf->Cell(25, 5, '4', 1, 0, 'C');
$pdf->Cell(25, 5, '440', 1, 1, 'C');

$pdf->Cell(20, 5, '5', 1, 0, 'C');
$pdf->Cell(25, 5, '119', 1, 0, 'C');
$pdf->Cell(65, 5, 'bottle', 1, 0, 'C');
$pdf->Cell(30, 5, '150', 1, 0, 'C');
$pdf->Cell(25, 5, '1', 1, 0, 'C');
$pdf->Cell(25, 5, '1500', 1, 1, 'C');

$pdf->Cell(125, 5, "", 0, 0);
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(40, 5, "Sub Total: Rs.", 0, 0, 'C');
$pdf->Cell(25, 5, "Rs." . '10840', 0, 1, 'L');
$pdf->Cell(125, 5, "", 0, 0);
$pdf->Cell(40, 5, "Tax Rate:", 0, 0, 'C');
$pdf->Cell(25, 5, '10%', 0, 1, 'L');
$pdf->SetFont("Times", "", 10);
$pdf->Cell(125, 5, "", 0, 0);
$pdf->Cell(40, 5, "Tax Amount: Rs.", 0, 0, 'C');
$pdf->Cell(25, 5, '1084', 0, 1, 'L');
$pdf->SetFont("Times", "B", 10);
$pdf->image("../../" . $company[0]['signature'], 15, 76, 20, 20);
$pdf->Cell(50, 5, "", 0, 0, "L");
$pdf->Cell(10, 5, "", 0, 0, "");
$pdf->Cell(45, 5, 'SalesPerson name', 0, 0, "C");
$pdf->Cell(20, 5, "", 0, 0, "");
$pdf->Cell(40, 5, "Total Amount: Rs.", 0, 0, 'C');
$pdf->Cell(25, 5, '11924', 0, 1, 'L');

$pdf->Cell(50, 5, "AUTHORISED SIGNATORY", 'T', 0, "L");
$pdf->Cell(10, 5, "", 0, 0, "");
$pdf->Cell(45, 5, "PREPARED BY", 'T', 0, "C");
$pdf->Cell(20, 5, "", 0, 0, "");
$pdf->Cell(40, 5, "Amount Paid: Rs.", 0, 0, 'C');
$pdf->Cell(25, 5, '10924', 0, 1, 'L');
$pdf->SetFont("Times", "I", 10);
$pdf->Cell(125, 20, "*fell free to visit our store again", 0, 0);
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(40, 5, "Amount Due: Rs.", 0, 0, 'C');
$pdf->Cell(25, 5, '1000', 0, 1, 'L');

$pdf->Output();

ob_end_flush();
