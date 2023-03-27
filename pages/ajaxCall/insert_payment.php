<?php
//echo $_POST['customerId'] . "and " . $_POST['payment_amt'];
session_start();
include '../../queries/Invoice.php';
include '../../queries/Customer.php';
$invoice = new Invoice();
$customer = new Customer();

if (isset($_POST['customerId'])) {

    $result = mysqli_fetch_assoc($row = $customer->getCustomerbyId($_POST['customerId']));
    $data = $invoice->customerTotalSum($_POST['customerId']);

    $total = $data['total'];
    $due = $data['due'];

    if ($_POST['payment_amt'] <= $due) {
        $_POST['payment_amt'];
        $invoice->payInvoiceDue($_POST['customerId'], $_POST['payment_amt']);
        $invoice->sendMessage("Payment of Rs." . $_POST['payment_amt'] . " by Customer: " . $result['first_name'] . " " . $result['last_name'] . ".");

        echo 1;
    } else {
        echo "Payment Cannot be more than due Amount!";
    }

    // $result = ["row" => $row, "total" => $total, "due" => $due];
    // echo json_encode($result);
} else {
    echo "no result";
}
