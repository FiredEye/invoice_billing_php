<?php
include '../../queries/Customer.php';
include '../../queries/Invoice.php';
$invoice = new Invoice();
$customer = new Customer();
if (isset($_POST['id'])) {
    $data = $customer->getCustomerbyId($_POST['id']);
    $row = mysqli_fetch_assoc($data);
    $output = "
            <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
            <thead class='thead-dark'>
                <tr style='font-size:13px;'>
                    <th>SN.</th>
                    <th>Issued Date</th>
                    <th>Due Date</th>
                    <th>Invoice No:</th>
                    <th>Invoice Total</th>
                    <th>Due Amount</th>
                    
                </tr>
            </thead>        
            <tbody style='font-size:15px;'>";
    $data = $invoice->getCustomerInvoicePay($_POST['id']);
    $sn = 1;
    $total = 0;
    $due = 0;
    while ($result = mysqli_fetch_assoc($data)) {
        $invoiceDate = date("d/M/Y", strtotime($result["order_date"]));
        $dueDate = date("d/M/Y", strtotime($result["due_date"]));

        $due = $result["order_total_amount_due"];
        if ($due == 0) {
            $due = 'Paid';
        }
        $output .= "<tr>
                    <td>" . $sn . "</td>
                    <td>" . $invoiceDate . "</td>
                    <td>" . $dueDate . "</td>
                    <td>" . $result["order_id"] . "</td>
                    <td>" . $result["order_total_after_tax"] . "</td>
                    <td>" . $due . "</td>
                </tr>";
        $sn++;
    }
    $output .= "
            </tbody></table>";
    echo $output;
} else {
    echo "no result";
}
