<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    $data = $invoice->totalAmt_invoiceCount($_POST);
    echo json_encode($data);
} else {
    echo "no result";
}
