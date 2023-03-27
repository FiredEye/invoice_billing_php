<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$branch = $_POST['branch'];
$result = $invoice->addBranch($branch);
if ($result) {
    echo ("New Branch Added");
} else {
    echo ("Some error occured!!");
}
