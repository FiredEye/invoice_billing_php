<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$result = $invoice->changePassword($_SESSION['userid'], $_POST['password']);
if ($result) {
    $_SESSION['change1'] = 'Password changed.';
    echo 1;
}
