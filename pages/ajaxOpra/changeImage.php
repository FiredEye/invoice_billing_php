<?php
session_start();
include '../../queries/Invoice.php';
$invoice = new Invoice();
$user = $invoice->loginUsers($_SESSION['email']);
unlink('../' . $user[0]['image']);
$image = $_FILES['image']['name'];
$destination = "../../img/" . $image;
move_uploaded_file($_FILES['image']['tmp_name'], $destination);
$result = $invoice->changeImage($_SESSION['userid'], $_FILES['image']);
if ($result) {
    $_SESSION['change'] = 'Profile Picture updated.';
    header('location:../profile.php');
}
