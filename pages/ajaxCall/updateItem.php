<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/item.php';
$invoice = new Invoice();
$item = new Item();
$nonName = 0;
$nonPrice = 0;
$nonQty = 0;
$nonMinQty = 0;

if ($_POST['itemName'] == '') {
    $nonName++;
}
if ($_POST['itemPrice'] == '' || $_POST['itemPrice'] <= 0) {
    $nonPrice++;
}
if ($_POST['itemQuantity'] == '' || $_POST['itemQuantity'] <= 0 || $_POST['itemMinQuantity'] == '' || $_POST['itemMinQuantity'] <= 0) {
    $nonQty++;
}
if ($_POST['itemQuantity'] == $_POST['itemMinQuantity'] || $_POST['itemQuantity'] < $_POST['itemMinQuantity']) {
    $nonMinQty++;
}

if ($nonName > 0) {
    echo "Item Name/s cannot be empty.";
} elseif ($nonPrice > 0) {
    echo "Item Price/s cannot be empty or negative.";
} elseif ($nonQty > 0) {
    echo "Quantity/s and Min. Quantity cannot be empty or negative.";
} elseif ($nonMinQty > 0) {
    echo "Minimum Quantity must be less than Item Quantity.";
} else {
    $item->updateItem($_POST);
    $invoice->sendMessage("Updated details of item code:" . $_POST['itemCode'] . ".");
    echo 1;
}
