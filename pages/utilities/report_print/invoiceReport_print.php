<?php
session_start();

if (isset($_GET['from_date']) && isset($_GET['to_date'])) {

    $_POST['from_date'] = $_GET['from_date'];
    $_POST['to_date'] = $_GET['to_date'];
    include '../../../queries/invoice.php';
    include '../../../queries/customer.php';
    $invoice = new Invoice();
    $customer = new Customer();
    $data = $invoice->getInvoice_fromDate($_POST);
    $data1 = $invoice->totalAmt_invoiceCount($_POST);
    $company = $invoice->companyInfo();
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
    $pdf->Output();
    //$pdf->Output('D','item_list.pdf',true);
    ob_end_flush();
} else {
    echo "page not found";
}
