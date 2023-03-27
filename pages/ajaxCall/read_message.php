<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$invoice->readMessage();
