<?php
session_start();
include '../../queries/Customer.php';
include '../../queries/Invoice.php';
$invoice = new Invoice();
$customer = new Customer();
if ($_POST['action'] == 'delete_customer' && $_POST['id']) {
	$customer->deleteCustomer($_POST['id']);
	$invoice->sendMessage("Deleted details of customer id:" . $_POST['id'] . ".");
	echo 1;
}
