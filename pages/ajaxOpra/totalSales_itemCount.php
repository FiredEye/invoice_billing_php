<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    $row = mysqli_num_rows($invoice->getItemSales_fromDate($_POST));
    $data = $invoice->totalItemSales($_POST);
    $total = $data['total'];
    $result = ["row" => $row, "total" => $total];
    echo json_encode($result);
} else {
    echo "no result";
}
