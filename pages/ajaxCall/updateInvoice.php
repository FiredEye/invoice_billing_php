<?php
session_start();
include '../../queries/Invoice.php';
include '../../queries/item.php';
$invoice = new Invoice();
$item = new Item();
$count2 = 0;
if (isset($_POST['productCode'])) {
    $same = '';
    for ($i = 0; $i < count($_POST['productCode']); $i++) {
        for ($k = $i + 1; $k < count($_POST['productCode']); $k++) {
            if ($_POST['productCode'][$i] == $_POST['productCode'][$k]) {
                $same = $_POST['productCode'][$i] . ',' . $same;
            }
        }
    }
    if (empty($_POST['customerId'])) {
        echo "Fill all the required information.";
    } elseif (!empty($_POST['customerId']) && $_POST['customerId']) {
        for ($i = 0; $i < count($_POST['productCode']); $i++) {
            if (empty($_POST['productCode'][$i])) {
                $count2++;
            }
        }
        if ($count2 > 0) {
            echo "Select Item Code";
        } elseif (empty($_POST['due_date'])) {
            echo "Fill Due Date.";
        } else {
            $count = 0;
            $count1 = 0;
            $OFS = '';
            $MS = '';
            for ($i = 0; $i < count($_POST['productCode']); $i++) {
                $data = $item->getItembyId($_POST['productCode'][$i]);
                if ($data['quantity'] == 0) {
                    $OFS = $_POST['productCode'][$i] . ", " . $OFS;
                }
                if ($_POST['quantity'][$i] > $data['quantity'] && $data['quantity'] > 0) {
                    $MS = $_POST['productCode'][$i] . " only " . $data['quantity'] . ", " . $OFS;
                }
                if ($_POST['quantity'][$i] == '' || $_POST['quantity'][$i] == 0 || $_POST['quantity'][$i] < 0) {
                    $count++;
                }
                if (empty($_POST['productCode'][$i])) {
                    $count1++;
                }
            }
            if ($same != '') {
                echo $same . ' is repeated.';
            } elseif ($count1 > 0) {
                echo "All fields must be filled!";
            } elseif ($OFS != '') {
                echo $OFS . " out of stock!!";
            } elseif ($MS != '') {
                echo  $MS . " available.";
            } elseif ($count > 0) {
                echo "Quantity should not be negative or empty";
            } elseif ($_POST['totalAftertax'] < $_POST['amountPaid'] || $_POST['amountPaid'] < 0) {
                echo 'The amount paid should not be negative or greater than total amount.';
            } elseif ($_POST['due_date'] < date('Y-m-d')) {
                echo "The due date can not be earlier than today.";
            } else {
                $invoice->updateInvoice($_POST);
                $invoice->sendMessage("Updated Invoice No:" . $_POST['invoiceId'] . ".");
                echo 1;
            }
        }
    }
} else {
    echo "Fields cannot be empty.";
}
