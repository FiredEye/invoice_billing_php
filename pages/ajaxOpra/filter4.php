
<?php

if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
    include '../../queries/invoice.php';
    include '../../queries/customer.php';
    $customer = new Customer();
    $invoice = new Invoice();
    $data = $invoice->getSalesPersonsales_fromDate($_POST);
    $output = "
            <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
            <thead class='thead-dark'>
                <tr style='font-size:13px;'>
                    <th>SN.</th>
                    <th>Sales Person</th>
                    <th>No.of Invoices</th>
                    <th>Total Invoice Amount</th>
                    <th>Total Payment Amount</th>
                </tr>
            </thead>        
            <tbody style='font-size:15px;'>";
    $count = 0;
    if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_assoc($data)) {

            $count++;
            $output .= '
                <tr>
                    <td>' . $count . '</td>
                    <td>' . $row["recorded_by"] . '</td>
                    <td>' . $row["sn"] . '</td>
                    <td>' . $row["total"] . '</td>
                    <td>' . $row["paid"] . '</td>
                </tr>';
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