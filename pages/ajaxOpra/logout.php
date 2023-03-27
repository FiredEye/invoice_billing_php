<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
if ($_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location:../login.php");
}
