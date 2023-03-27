<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
if ($_POST['action'] == 'delete_invoice' && $_POST['id']) {
	$invoice->deleteInvoice($_POST['id']);
	$invoice->sendMessage("Deleted Invoice No:" . $_POST['id'] . ".");
	echo 1;
}
