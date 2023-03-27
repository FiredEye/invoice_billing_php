<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/Item.php';
$item = new Item();
$invoice = new Invoice();
$row = $invoice->getDueInvoices();
$row1 = $item->getLowStock();
$lowStock = mysqli_num_rows($row1);
date_default_timezone_set('Asia/Kathmandu');
$date = date('Y/m/d');
$count = 0;
while ($data = mysqli_fetch_assoc($row)) {

    $dueDate1 = strtotime($data["due_date"]);
    $due_day = ((strtotime($date) - $dueDate1) / 60 / 60 / 24);
    if ($due_day > 0) {
        $count++;
    }
}
$array = ['dueInvoice' => $count, 'lowStock' => $lowStock];
echo json_encode($array);
