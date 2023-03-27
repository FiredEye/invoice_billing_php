
<?php
session_start();

if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    include '../../queries/invoice.php';
    include '../../queries/customer.php';
    $customer = new Customer();
    $invoice = new Invoice();
    $data = $invoice->getInvoice_fromDate($_POST);
    if ($_SESSION['role'] == 'Admin') {
        $output = "
        <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
        <thead class='thead-dark'>
        <tr style='font-size:13px;'>
            <th>Create Date</th>
            <th>Recorded By</th>
            <th>Invoice No.</th>
            <th>Customer Name</th>                
            <th>Status</th>
            <th>Tax</th>
            <th>Invoice Total</th>
        </tr>
        </thead>        
        <tbody style='font-size:15px;'>";
    } else {
        $output = "
    <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
    <thead class='thead-dark'>
    <tr style='font-size:13px;'>
        <th>Create Date</th>
        <th>Invoice No.</th>
        <th>Customer Name</th>                
        <th>Status</th>
        <th>Tax</th>
        <th>Invoice Total</th>
    </tr>
    </thead>        
    <tbody style='font-size:15px;'>";
    }

    if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $row1 = $invoice->getSalesPersonById($row["recorded_by"]);
            $data1 = $customer->getCustomerbyId($row["order_receiver_id"]);
            $result = mysqli_fetch_assoc($data1);
            $invoiceDate = date("d/M/Y", strtotime($row["order_date"]));

            if ($row["order_amount_paid"] == $row["order_total_after_tax"]) {
                $status = "Paid";
            } elseif ($row["order_amount_paid"] == 0) {
                $status = "Not Paid";
            } elseif ($row["order_amount_paid"] < $row["order_total_after_tax"]) {
                $status = "Partially Paid";
            }
            if ($row["order_tax_per"] > 0) {
                $tax = $row["order_tax_per"] . " %";
            } else {
                $tax = $row["order_tax_per"];
            }
            if ($_SESSION['role'] == 'Admin') {
                $output .= '
            <tr>
            <td>' . $invoiceDate . '</td>
            <td>' . $row1["username"] . '</td>
            <td>' . $row["order_id"] . '</td>
            <td>' .  $result['first_name'] . " " . $result['last_name'] . '</td>
            <td>' . $status . '</td>
            <td>' . $tax . '</td>
            <td>' . $row["order_total_after_tax"] . '</td>
         </tr>';
            } else {
                $output .= '
            <tr>
            <td>' . $invoiceDate . '</td>
            <td>' . $row["order_id"] . '</td>
            <td>' .  $result['first_name'] . " " . $result['last_name'] . '</td>
            <td>' . $status . '</td>
            <td>' . $tax . '</td>
            <td>' . $row["order_total_after_tax"] . '</td>
         </tr>';
            }
        }
    } else {
        $output .= '
                <tr>
                    <td colspan="9">No Data Found</td>
                </tr>';
    }
    $output .= "</tbody></table>";
    echo $output;
}

?>