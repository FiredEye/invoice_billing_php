<?php
session_start();

if (isset($_GET['from_date']) && isset($_GET['to_date'])) {

    $_POST['from_date'] = $_GET['from_date'];
    $_POST['to_date'] = $_GET['to_date'];
    include '../../../queries/invoice.php';
    $invoice = new Invoice();
    $company = $invoice->companyInfo();
    $data = $invoice->getItemSales_fromDate($_POST);
    $row = mysqli_num_rows($data);
    $totalSales = $invoice->totalItemSales($_POST);
    $total = $totalSales['total'];
    $data1 = ["row" => $row, "total" => $total];
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

    $pdf->Cell(95, 5, "Item Sales Report:", "T", 0, 'L');
    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(95, 5, "Number of Items:" . $data1['row'], "T", 1, 'R');
    $pdf->Cell(95, 5, "Period:" . $_GET['from_date'] . " to " . $_GET['to_date'], "B", 0, 'L');
    $pdf->Cell(95, 5, "Total Sales:" . $data1['total'], "B", 1, 'R');
    //  $pdf->Cell(0, 10, "", 'T', 1, 'C');


    $pdf->Cell(0, 2, "", 0, 1);
    //index-title

    $pdf->SetFont("Times", "B", 10);
    $pdf->Cell(15, 5, "S.N.", 1, 0, 'C');
    $pdf->Cell(25, 5, "Code", 1, 0, 'C');
    $pdf->Cell(48, 5, "Item Name", 1, 0, 'C');
    $pdf->Cell(34, 5, "Quantity", 1, 0, 'C');
    $pdf->Cell(34, 5, "Price", 1, 0, 'C');
    $pdf->Cell(34, 5, "Sold Value", 1, 1, 'C');
    $pdf->SetFont("Times", "", 10);
    $count = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $count++;
        $pdf->Cell(15, 5, $count, 1, 0, 'L');
        $pdf->Cell(25, 5, $row['item_code'], 1, 0, 'L');
        $pdf->Cell(48, 5, $row['item_name'], 1, 0, 'C');
        $pdf->Cell(34, 5, $row['quantity'], 1, 0, 'L');
        $pdf->Cell(34, 5, $row['order_item_price'], 1, 0, 'C');
        $pdf->Cell(34, 5, $row['total'], 1, 1, 'C');
    }
    $pdf->Output();
    //$pdf->Output('D','item_list.pdf',true);
    ob_end_flush();
} else {
    echo "page not found";
}
