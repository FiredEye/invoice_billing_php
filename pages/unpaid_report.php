<?php
session_start();
include('../inc/header.php');

include '../queries/Invoice.php';
include '../queries/Customer.php';
$customer = new Customer();
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>


<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Unpaid Account Report:
            </h6><?php $value = $invoice->getUnpaidAmtRow()
                    ?>



            <a href="utilities/report_print/unpaidInvoiceReport_print.php" class="btn btn-success float-right" title="Print Invoice Report" target="_blank">

                <i class="fa fa-print"></i>&nbsp;Print

            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#inventoryModal" class="btn float-right text-white mr-3" title=" Email Invoice Report" style="background-color:#F5761A;">
                <i class="fa fa-envelope"></i>&nbsp;Email
            </a>
            <div class="bg-success ms-4 px-2 float-left" id="email_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                <?php
                if (isset($_SESSION['email_msg'])) {
                    echo $_SESSION['email_msg'];
                }
                ?>
            </div>
        </div>
        <div class="card-body text-black">
            <div class="row mb-2" style="color: black;">
                <div class="col-sm-12 col-md-3 col-lg-3" id="period1">As of: <?php echo date('Y-m-d'); ?></div>
                <div class="col-sm-12 col-md-3 col-lg-3" id="invoiceCount">Number of unpaid Invoices:<?php echo $value[0]['row']; ?></div>

                <div class="col-sm-12 col-md-3 col-lg-3" id="totalSum">Total Amount:<?php echo $value[0]['total']; ?></div>
                <div class="col-sm-12 col-md-3 col-lg-3" id="totalDue">Total Amount Due:<?php echo $value[0]['due']; ?></div>
            </div>
            <hr class="divider d-md-block bg-dark" />
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Customer</th>
                            <th>Balance Due</th>
                            <th>Due Date</th>
                            <th>Days Overdue</th>
                            <th>Invoice No.</th>
                            <?php if ($_SESSION['role'] == 'Admin') { ?>
                                <th>Recorded By</th>
                            <?php } ?>
                            <th>Invoice Total</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php

                        $invoiceList = $invoice->getInvoiceList();
                        foreach ($invoiceList as $invoiceDetails) {
                            $row = $invoice->getSalesPersonById($invoiceDetails["recorded_by"]);
                            $data1 = $customer->getCustomerbyId($invoiceDetails["order_receiver_id"]);
                            $result = mysqli_fetch_assoc($data1);
                            // $invoiceDate = date("d/M/Y", strtotime($invoiceDetails["order_date"]));
                            //$dueDate = date("d/M/Y", strtotime($invoiceDetails["due_date"]));
                            date_default_timezone_set('Asia/Kathmandu');
                            $date = date('Y/m/d');
                            $dueDate1 = strtotime($invoiceDetails["due_date"]);
                            $due_day = ((strtotime($date) - $dueDate1) / 60 / 60 / 24);
                            if ($due_day < 0) {
                                $due_day = 0;
                            }
                            if ($invoiceDetails["order_total_amount_due"] > 0) {
                                if ($_SESSION['role'] == 'Admin') {
                                    echo '
                                    <tr>
                                        <td>' . $result['first_name'] . " " . $result['last_name'] . '</td>
                                        <td>' . $invoiceDetails["order_total_amount_due"] . '</td>
                                        <td>' . $invoiceDetails["due_date"] . '</td>
                                        <td>' . $due_day . '</td>
                                        <td>' . $invoiceDetails["order_id"] . '</td>
                        
                                        <td>' . $row['username'] . '</td>
                                        <td>' . $invoiceDetails["order_total_after_tax"] . '</td>
                                    </tr>
                        ';
                                } else {
                                    echo '
                            <tr>
                                <td>' . $result['first_name'] . " " . $result['last_name'] . '</td>
                                <td>' . $invoiceDetails["order_total_amount_due"] . '</td>
                                <td>' . $invoiceDetails["due_date"] . '</td>
                                <td>' . $due_day . '</td>
                                <td>' . $invoiceDetails["order_id"] . '</td>
                
                                
                                <td>' . $invoiceDetails["order_total_after_tax"] . '</td>
                            </tr>
                ';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title" id="inventoryModalLabel">Send Report To:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="recipient_name" class="col-form-label">Recipient Email-ID:</label>
                        <input type="email" class="form-control" id="recipient_name" placeholder="Enter Valid Email-ID" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Please enter valid Email-ID</p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="send">Send</button>

            </div>
        </div>
    </div>
</div>
<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(() => {
        $('#errorMsg').hide();
        $('#send').click(() => {
            var email = $('#recipient_name').val();
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var result = re.test(email);
            if (email == '' || result == 0) {
                $('#errorMsg').show();
                $('#errorMsg').delay(1500).fadeOut(500);
            } else {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();
                window.open('utilities/report_email/unpaidInvoiceReport_email.php?email=' + email, '_self');
            }
        })
        $('#email_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['email_msg'] = '' ?>
    })
</script>