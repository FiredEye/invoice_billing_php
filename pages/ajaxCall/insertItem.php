<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/item.php';
$invoice = new Invoice();
$item = new Item();
$nonCode = 0;
$nonName = 0;
$nonPrice = 0;
$nonQty = 0;
$nonMinQty = 0;
$sameCode = '';
if (isset($_POST['itemCode'])) {
    $same = '';
    for ($i = 0; $i < count($_POST['itemCode']); $i++) {
        for ($k = $i + 1; $k < count($_POST['itemCode']); $k++) {
            if ($_POST['itemCode'][$i] == $_POST['itemCode'][$k]) {
                $same = $_POST['itemCode'][$i] . ',' . $same;
            }
        }
    }


    for ($i = 0; $i < count($_POST['itemCode']); $i++) {
        if (!empty($_POST['itemCode'][$i])) {
            $_POST['code'] = $_POST['itemCode'][$i];
            $data = $item->getNamePrice($_POST['code']);
            if (!empty($data)) {
                $sameCode = $_POST['itemCode'][$i] . ", " . $sameCode;
            }
        }

        if (empty($_POST['itemCode'][$i])) {
            $nonCode++;
        }
        if ($_POST['itemName'][$i] == '') {
            $nonName++;
        }
        if ($_POST['itemPrice'][$i] == '' || $_POST['itemPrice'][$i] <= 0) {
            $nonPrice++;
        }
        if ($_POST['itemQuantity'][$i] == '' || $_POST['itemQuantity'][$i] <= 0 || $_POST['itemMinQuantity'][$i] == '' || $_POST['itemMinQuantity'][$i] <= 0) {
            $nonQty++;
        }
        if ($_POST['itemQuantity'][$i] == $_POST['itemMinQuantity'][$i] || $_POST['itemQuantity'][$i] < $_POST['itemMinQuantity'][$i]) {
            $nonMinQty++;
        }
    }
    if ($same != '') {
        echo $same . ' is repeated.';
    } elseif ($sameCode != '') {
        echo $sameCode . " has already been registered.";
    } elseif ($nonCode > 0) {
        echo "Item Code/s cannot be empty.";
    } elseif ($nonName > 0) {
        echo "Item Name/s cannot be empty.";
    } elseif ($nonPrice > 0) {
        echo "Item Price/s cannot be empty or negative.";
    } elseif ($nonQty > 0) {
        echo "Quantity/s and Min. Quantity cannot be empty or negative.";
    } elseif ($nonMinQty > 0) {
        echo "Minimum Quantity must be less than Item Quantity.";
    } else {
        $item->saveItem($_POST);
        $invoice->sendMessage('Added new Item/s.');
        echo 1;
    }
} else {
    echo "Fields cannot be empty.";
}
