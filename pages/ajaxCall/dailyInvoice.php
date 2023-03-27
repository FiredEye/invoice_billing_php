<?php
session_start();
include '../../queries/Invoice.php';

$invoice = new Invoice();
$data = $invoice->getDailyTransaction();
//$array = [];
$totalAmount = 0;
$paid = 0;
$due = 0;
$row = mysqli_num_rows($data);
while ($result = mysqli_fetch_assoc($data)) {
    $totalAmount = $result["order_total_after_tax"] + $totalAmount;
    $paid = $result["order_amount_paid"] + $paid;
    $due = $result["order_total_amount_due"] + $due;
}

//echo $due;
$array = [$row, $totalAmount, $paid, $due];
echo json_encode($array);
