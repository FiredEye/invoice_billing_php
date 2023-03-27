<?php
if (isset($_POST['from_date']) && isset($_POST['to_date']) && isset($_POST['id'])) {
    include '../../queries/invoice.php';
    $invoice = new Invoice();
    $data = $invoice->getSPItemSales_fromDate($_POST);
    $output = "
       
            <table class='table table-bordered table-sm text-black' id='dataTable' width='100%' cellspacing='0'>
            <thead class='thead-dark'>
                <tr style='font-size:13px;'>
                    <th>SN.</th>
                    <th>Code</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Sold Value</th>
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
                    <td>' . $row["item_code"] . '</td>
                    <td>' . $row["item_name"] . '</td>
                    <td>' . $row["quantity"] . '</td>
                    <td>' . $row["order_item_price"] . '</td>
                    <td>' . $row["total"] . '</td>
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
