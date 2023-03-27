<?php
session_start();
include '../../queries/Customer.php';
include '../../queries/Invoice.php';
$customer = new Customer();
$invoice = new Invoice();
$nonName = 0;
$nonAdds = 0;
$nonNum = 0;
$nonEmail = 0;
if (!preg_match("/^[A-Za-z]+$/", $_POST['customerFName']) || !preg_match("/^[A-Za-z]+$/", $_POST['customerLName'])) {
    $nonName++;
}
if (!preg_match("/^[A-Za-z]+$/", $_POST['customerAddress'])) {
    $nonAdds++;
}

if (!preg_match("/^9[0-9]{9}$/", $_POST['customerPhone'])) {
    $nonNum++;
}

if (!filter_var($_POST['customerEmail'], FILTER_VALIDATE_EMAIL)) {
    $nonEmail++;
}

if ($nonName > 0) {
    echo "Enter Name properly!";
} elseif ($nonAdds > 0) {
    echo "Enter Addresse properly!";
} elseif ($nonNum > 0) {
    echo "Phone No. must start with 9 and must have 10 digits";
} elseif ($nonEmail > 0) {
    echo "Entered Email format doesn't matches";
} else {
    $result = $customer->updateCustomer($_POST);
    $invoice->sendMessage("Updated details of customer id:" . $_POST['id'] . ".");
    if ($result) {
        echo 1;
    }
}
