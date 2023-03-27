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
$sameNum = '';
$sameEmail = '';
if (isset($_POST['customerFName'])) {
    for ($i = 0; $i < count($_POST['customerFName']); $i++) {
        if (!preg_match("/^[A-Za-z]+$/", $_POST['customerFName'][$i]) || !preg_match("/^[A-Za-z]+$/", $_POST['customerLName'][$i])) {
            $nonName++;
        }
        if (!preg_match("/^[A-Za-z]+$/", $_POST['customerAddress'][$i])) {
            $nonAdds++;
        }

        if (!preg_match("/^9[0-9]{9}$/", $_POST['customerPhone'][$i])) {
            $nonNum++;
        }
        if (!empty($_POST['customerPhone'][$i])) {

            $_POST['num'] = $_POST['customerPhone'][$i];
            $data = $customer->getCustomerbyNumber($_POST['num']);
            $row = mysqli_num_rows($data);
            if ($row > 0) {
                $sameNum = $_POST['customerPhone'][$i] . ", " . $sameNum;
            }
        }
        if (!filter_var($_POST['customerEmail'][$i], FILTER_VALIDATE_EMAIL)) {
            $nonEmail++;
        }
        if (!empty($_POST['customerEmail'][$i])) {

            $_POST['email'] = $_POST['customerEmail'][$i];
            $data = $customer->getCustomerbyEmail($_POST['email']);
            $row = mysqli_num_rows($data);
            if ($row > 0) {
                $sameEmail = $_POST['customerEmail'][$i] . ", " . $sameEmail;
            }
        }
    }

    if ($nonName > 0) {
        echo "Enter Name/s properly!";
    } elseif ($nonAdds > 0) {
        echo "Enter Addresse/s properly!";
    } elseif ($nonNum > 0) {
        echo "Phone No. must start with 9 and must have 10 digits.";
    } elseif ($sameNum != '') {
        echo $sameNum . " has already been registered.";
    } elseif ($nonEmail > 0) {
        echo "Entered Email/s format doesn't matches.";
    } elseif ($sameEmail != '') {
        echo $sameEmail . " has already been registered.";
    } else {
        $customer->saveCustomer($_POST);
        $invoice->sendMessage("Added new Customer/s");
        echo 1;
    }
} else {
    echo "Fields cannot be empty.";
}
