<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$result = $invoice->changeAddress($_SESSION['userid'], $_POST['address']);
if ($result) {
    $_SESSION['change1'] = 'Address changed.';
    header('location:../profile.php');
}
