<?php
session_start();
include '../../queries/item.php';
$item = new Item();
include '../../queries/invoice.php';
$invoice = new Invoice();
$item->checkLoggedIn();

$company = $invoice->companyInfo();

$itemsList = $item->getItemlist();
$row = $item->getNumRows_Item();
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
$pdf->Cell(0, 1, "", 'B', 1, 'C');
$pdf->Cell(25, 5, "Items List:", 'B', 1);
$pdf->Cell(0, 2, "", 0, 1);
//index-title
$pdf->SetFont("Times", "B", 10);
$pdf->Cell(20, 5, "Sr No.", 1, 0, 'L');
$pdf->Cell(24, 5, "Item Code", 1, 0, 'L');
$pdf->Cell(70, 5, "Item Name", 1, 0, 'L');
$pdf->Cell(38, 5, "Price", 1, 0, 'L');
$pdf->Cell(38, 5, "Quantity", 1, 1, 'L');
$pdf->SetFont("Times", "", 10);
$count = 0;
foreach ($itemsList as $itemList) {
    $count++;
    $pdf->Cell(20, 5, $count, 1, 0, 'C');
    $pdf->Cell(24, 5, $itemList['code'], 1, 0, 'C');
    $pdf->Cell(70, 5, $itemList['name'], 1, 0, 'C');
    $pdf->Cell(38, 5, $itemList['price'], 1, 0, 'C');
    $pdf->Cell(38, 5, $itemList['quantity'], 1, 1, 'C');
}
$pdf->Output();
//$pdf->Output('D','item_list.pdf',true);
ob_end_flush();
