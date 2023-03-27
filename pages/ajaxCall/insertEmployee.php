<?php
include '../../queries/Invoice.php';

$invoice = new Invoice();
$nonNum = 0;
$nonEmail = 0;
$sameNum = '';
$sameEmail = '';



if (isset($_POST['firstName'])) {


    if (!preg_match("/^9[0-9]{9}$/", $_POST['phone'])) {
        $nonNum++;
    }
    if (!empty($_POST['phone'])) {


        $data = $invoice->checkPhone($_POST['phone']);

        if ($data > 0) {
            $sameNum = $_POST['phone'];
        }
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $nonEmail++;
    }
    if (!empty($_POST['email'])) {


        $data = $invoice->checkEmail($_POST['email']);
        if ($data > 0) {
            $sameEmail = $_POST['email'];
        }
    }
    if ($_POST['addEdit'] == 'add') {
        if ($nonNum > 0) {
            echo "Phone No. must start with 9 and must have 10 digits.";
        } elseif ($sameNum != '') {
            echo $sameNum . " has already been registered.";
        } elseif ($nonEmail > 0) {
            echo "Entered Email/s format doesn't matches.";
        } elseif ($sameEmail != '') {
            echo $sameEmail . " has already been registered.";
        } else {
            $_POST['name'] = $_POST['firstName'] . ' ' . $_POST['lastName'];
            $result = $invoice->signinUID($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'], $_POST['cpassword'], $_POST['uId']);
            if ($result[0])  echo 1;
        }
    } else {
        if ($nonNum > 0) {
            echo "Phone No. must start with 9 and must have 10 digits.";
            // } elseif ($sameNum != '') {
            //     echo $sameNum . " has already been registered.";
        } elseif ($nonEmail > 0) {
            echo "Entered Email/s format doesn't matches.";
            // } elseif ($sameEmail != '') {
            //     echo $sameEmail . " has already been registered.";
        } else {
            $_POST['name'] = $_POST['firstName'] . ' ' . $_POST['lastName'];
            $result = $invoice->signinUID($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['password'], $_POST['cpassword'], $_POST['uId']);
            if ($result[0])  echo 2;
        }
    }
} else {
    echo "Fields cannot be empty.";
}
