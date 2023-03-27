<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$result = $invoice->changeNumber($_SESSION['userid'], $_POST['number']);
if ($result) {
    $_SESSION['change1'] = 'Moblie Number changed.';
    header('location:../profile.php');
}
