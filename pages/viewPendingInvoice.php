<?php
session_start();
include('../inc/header.php');
include '../queries/Customer.php';
$customer = new Customer();

include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>
<title>Invoice System</title>
<script src="../js/invoice.js"></script>
<style>
    table {
        font-family: Arial, Helvetica, sans-serif;
    }

    th,
    td {
        text-align: center;
    }
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>


<div class="container-fluid" id="invoice_list">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Pending Invoice-List Table
            </h6>
            <div class="bg-success px-2 ms-3" id="email_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                <?php
                if (isset($_SESSION['email_msg'])) {
                    echo $_SESSION['email_msg'];
                }
                ?>
            </div>
        </div>
        <div class="card-body text-black">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="myTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Invoice No.</th>
                            <th>Customer Name</th>
                            <th>Invoice Total</th>
                            <th>Total Due</th>
                            <th>Create Date</th>
                            <th>Due Days</th>
                            <?php if ($_SESSION['role'] == "Admin") { ?> <th>Recorded By</th><?php } ?>

                            <th>E-Mail</th>


                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php
                        $row = $invoice->getDueInvoices();
                        date_default_timezone_set('Asia/Kathmandu');
                        $date = date('Y/m/d');

                        while ($data = mysqli_fetch_assoc($row)) {
                            $salesPerson = $invoice->getSalesPersonById($data["recorded_by"]);

                            $data1 = $customer->getCustomerbyId($data["order_receiver_id"]);
                            $invoiceDate = date("d/M/Y", strtotime($data["order_date"]));
                            $dueDate1 = strtotime($data["due_date"]);
                            $due_day = ((strtotime($date) - $dueDate1) / 60 / 60 / 24);
                            $customerDetail = mysqli_fetch_assoc($data1);


                            if ($due_day > 0) {


                                if ($_SESSION['role'] == "Admin") {
                                    echo '
                                    <tr>
                                      <td>' . $data["order_id"] . '</td>
                                      <td>' . $customerDetail['first_name'] . ' ' . $customerDetail['last_name'] . '</td>
                                      <td>' . $data["order_total_after_tax"] . '</td>
                                      <td>' . $data["order_total_amount_due"] . '</td>
                                      <td>' . $invoiceDate . '</td>
                                      <td>' . $due_day . '</td>
                                      <td>' . $salesPerson['username'] . '</td>
                                      <td><a href="utilities/email_invoice.php?invoice_id=' . $data['order_id'] . '" title="Mail Invoice"><i class="fa fa-envelope " style="color:#F5761A" aria-hidden="true"></i></a></td>
                                     </tr>
                                  ';
                                } else {
                                    echo '
                                <tr>
                                  <td>' . $data["order_id"] . '</td>
                                  <td>' . $customerDetail['first_name'] . ' ' . $customerDetail['last_name'] . '</td>
                                  <td>' . $data["order_total_after_tax"] . '</td>
                                  <td>' . $data["order_total_amount_due"] . '</td>
                                  <td>' . $invoiceDate . '</td>
                                  <td>' . $due_day  . '</td>
                                  <td><a href="utilities/email_invoice.php?invoice_id=' . $data['order_id'] . '" title="Mail Invoice"><i class="fa fa-envelope " style="color:#F5761A" aria-hidden="true"></i></a></td>
                                 </tr>
                              ';
                                }
                            }

                        ?>

                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            ordering: false
        });
        $('#email_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['email_msg'] = ''
        ?>


    })
</script>