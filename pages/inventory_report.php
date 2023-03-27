<?php
session_start();
include('../inc/header.php');

include '../queries/item.php';
$item = new Item();
include '../queries/Invoice.php';
$invoice = new Invoice();
$item->checkLoggedIn();
?>


<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Inventory Report Table:
            </h6><?php $value = $item->getItemAmtQty()
                    ?>



            <a href="utilities/report_print/inventoryReport_print.php" class="btn btn-success float-right" title="Print Inventory Report" target="_blank">

                <i class="fa fa-print"></i>&nbsp;Print

            </a>
            <a href="#" data-bs-toggle="modal" data-bs-target="#inventoryModal" class="btn float-right text-white mr-3" title=" Email Inventory Report" style="background-color:#F5761A;">
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
            <div class="row mb-2 text-black">
                <div class="col-sm-12 col-md-4 col-lg-4" id="period1">Date: <?php echo date('Y-m-d'); ?></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="invoiceCount">Total Quanity:<?php echo $value[0]['quantity']; ?></div>

                <div class="col-sm-12 col-md-4 col-lg-4" id="totalSum">Total Amount:<?php echo $value[0]['total']; ?></div>
            </div>
            <hr class="divider d-md-block bg-dark " />
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Code</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Min. Quantity</th>
                            <th>Price</th>

                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php

                        $itemList = $item->getItemList();
                        foreach ($itemList as $itemDetails) {
                            echo '
                                        <tr>
                                            <td>' . $itemDetails["code"] . '</td>
                                            <td>' . $itemDetails["name"] . '</td>
                                            <td>' . $itemDetails["quantity"] . '</td>
                                            <td>' . $itemDetails["min_quantity"] . '</td>
                                            <td>' . $itemDetails["price"] . '</td>
                                        </tr>
            ';
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
                window.open('utilities/report_email/inventoryReport_email.php?email=' + email, '_self');
            }
        })
        $('#email_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['email_msg'] = '' ?>
    })
</script>