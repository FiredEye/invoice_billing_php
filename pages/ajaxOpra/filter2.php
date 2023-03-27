
<?php
session_start();
if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    include '../../queries/invoice.php';
    include '../../queries/customer.php';
    $customer = new Customer();
    $invoice = new Invoice();
    $data = $invoice->getCustomerSales_fromDate($_POST);
    if ($_SESSION['role'] == 'Admin') {
        $output = "
        <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
        <thead class='thead-dark'>
             <tr style='font-size:13px;'>
                <th>SN.</th>
                <th>Recorded By</th>
                <th>Customer</th>
                <th>Total Invoice Amount</th>
                <th>Total Payment Amount</th>
            </tr>
        </thead>        
        <tbody style='font-size:15px;'>";
    } else {
        $output = "
            <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
            <thead class='thead-dark'>
                 <tr style='font-size:13px;'>
                    <th>SN.</th>
                    <th>Customer</th>
                    <th>Total Invoice Amount</th>
                    <th>Total Payment Amount</th>
                </tr>
            </thead>        
            <tbody style='font-size:15px;'>";
    }
    $count = 0;
    if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {
            $data1 = $customer->getCustomerbyId($row["order_receiver_id"]);
            $result = mysqli_fetch_assoc($data1);
            $count++;
            if ($_SESSION['role'] == 'Admin') {
                $salesperson = $invoice->getSalesPersonById($row['recorded_by']);
                $output .= '
                <tr>
                    <td>' . $count . '</td>
                    <td>' . $salesperson['username'] . '</td>
                    <td>' .  $result['first_name'] . " " . $result['last_name'] . '</td>
                    <td>' . $row["total"] . '</td>
                    <td>' . $row["paid"] . '</td>
                </tr>';
            } else {
                $output .= '
                <tr>
                    <td>' . $count . '</td>
                    <td>' .  $result['first_name'] . " " . $result['last_name'] . '</td>
                    <td>' . $row["total"] . '</td>
                    <td>' . $row["paid"] . '</td>
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