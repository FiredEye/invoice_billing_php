<?php
// if ($_FILES['signature']['error'] != 0) {
//     echo 'no file';

// } else {
//     echo 'file';
// }

session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
if ($_FILES['signature']['error'] != 0) {
    $result = $invoice->changeCompanyDetail($_POST['name'], $_POST['email'], $_POST['number'], $_POST['address'], $_POST['password']);
    if ($result) {
        $_SESSION['company1'] = 'Company details Updated.';
        header('location:../company.php');
    }
} else {
    $company = $invoice->companyInfo();
    unlink('../' . $company[0]['signature']);
    $signature = $_FILES['signature']['name'];
    $destination = "../../companyImg/" . $signature;
    move_uploaded_file($_FILES['signature']['tmp_name'], $destination);
    $result1 = $invoice->changeCompanyDetail1($_POST['name'], $_POST['email'], $_POST['number'], $_POST['address'], $_POST['password'], $_FILES['signature']);
    if ($result1) {
        $_SESSION['company1'] = 'Company details Updated.';
        header('location:../company.php');
    }
}
