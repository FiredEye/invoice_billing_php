<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();

if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    $branch = $invoice->getSalesPersonById($_POST['id']);
    $row = mysqli_num_rows($invoice->getSPItemSales_fromDate($_POST));
    $data = $invoice->totalItemSalesSP($_POST);
    $total = $data['total'];
    //$SPbranch=$branch['branch'];
    $result = ["row" => $row, "total" => $total, "branch" => $branch['branch'], "salesPerson" => $branch['username']];
    echo json_encode($result);
} else {
    echo "no result";
}
