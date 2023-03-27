<?php
session_start();
include '../../queries/item.php';
include '../../queries/Invoice.php';
$invoice = new Invoice();
$item = new Item();
if ($_POST['action'] == 'delete_item' && $_POST['id']) {
	$item->deleteItem($_POST['id']);
	$invoice->sendMessage("Deleted details of Item code:" . $_POST['id'] . ".");
	echo 1;
}
