<?php

include '../../queries/Invoice.php';
$invoice = new Invoice();
include '../../queries/Customer.php';
$customer = new Customer();

if (isset($_POST['id'])) {
    $row = mysqli_num_rows($invoice->getCustomerInvoice($_POST['id']));
    $data = $invoice->customerTotalSum($_POST['id']);
    $customerDetail = $customer->getCustomerbyId($_POST['id']);
    $total = $data['total'];
    $due = $data['due'];
    foreach ($customerDetail as $customerEmail) {
        $email = $customerEmail['email'];
        $name = $customerEmail['first_name'] . ' ' . $customerEmail['last_name'];
    }

    $result = ["row" => $row, "total" => $total, "due" => $due, "email" => $email, "name" => $name];
    echo json_encode($result);
} else {
    echo "no result";
}
