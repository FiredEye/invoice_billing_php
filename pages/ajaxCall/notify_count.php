<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$result = $invoice->getUnreadMessage();
$unread_count = mysqli_num_rows($result);
echo $unread_count;
