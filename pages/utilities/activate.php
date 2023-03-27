<?php
session_start();
include '../../queries/invoice.php';
$invoice = new Invoice();
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $result = $invoice->changeStatus($token);
    if ($result) {
        if (isset($_SESSION['message'])) {
            $_SESSION['message'] = "Account activated successfully!!";
            header('location:../login.php');
        } else {
            $_SESSION['message'] = "You are logged out";
            header('location:../login.php');
        }
    }
} else {
    $_SESSION['message'] = "Account not activated!!";
    header('location:../login.php');
}
