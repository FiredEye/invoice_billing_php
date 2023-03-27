<?php
session_start();
include('../inc/header.php');

include '../queries/Invoice.php';
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
                Item Sales Report:
            </h6>

            <div id="util">
                <a href="#" class="btn btn-success float-right" id="print" title="Print Item Sales Report" target="_blank">
                    <i class="fa fa-print"></i>&nbsp;Print
                </a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#inventoryModal" class="btn float-right text-white mr-3" id="email" title=" Email Item Sales Report" style="background-color:#F5761A;">
                    <i class="fa fa-envelope"></i>&nbsp;Email
                </a>
            </div>
            <div class="bg-success ms-4 px-2 float-left" id="email_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
                <?php
                if (isset($_SESSION['email_msg'])) {
                    echo $_SESSION['email_msg'];
                }
                ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row py-2">
                <div class="col-sm-12 col-md-3" style="float:left;">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="From Date" autocomplete="off">
                </div>
                <div class="col-sm-12 col-md-3" style="float:left;">
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="To Date" autocomplete="off">
                </div>

                <div class="col-sm-12 col-md-5" style="float:left;">
                    <input type="button" name="filter" id="filter" value="filter" class="btn btn-info " style="float:left;">
                    <p class="bg-danger ms-5 px-2 mt-1" id="d_msz" style="color:white; border-radius:8px;float:left;">Select Date on both column!</p>
                    <p class="bg-danger ms-5 px-2 mt-1" id="d1_msz" style="color:white; border-radius:8px;float:left;">Select date in ascending order!</p>

                </div>

            </div>

            <div class="row mb-2" id="content1" style="color: black;">

                <div class="col-sm-12 col-md-4 col-lg-4" id="period"></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="itemCount"></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="totalSales"></div>
            </div>
            <hr class="divider d-md-block bg-dark" />
            <div class="table-responsive">
                <p style="color:black;"> Select date to get Item Sales Reports<span style="color: red;">*</span></p>
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

<!-- <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'> -->
<link rel="stylesheet" href="../css/offline/1.12.1theme-ui-light-jquery-ui.css">
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script src="../js/offline/3.4.1jquery.min.js"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"> -->
<!-- </script> -->
<script src="../js/offline/1.12.1jquery-ui.min.js"></script>
<script>
    $(document).ready(() => {
        $('#util').hide();
        $('#d_msz').hide();
        $('#d1_msz').hide();
        $('#errorMsg').hide();
        $('#content1').hide();
        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });
        $('#from_date').datepicker({

            maxDate: new Date()
        });
        $('#to_date').datepicker({

            maxDate: new Date()
        });
        $('#from_date').datepicker();
        $('#to_date').datepicker();
        $('#filter').click(() => {

            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            if (from_date != '' && to_date != '') {
                if (from_date < to_date) {
                    $('#period').html("Peroid: " + from_date + " to " + to_date)

                    $.ajax({
                        url: 'ajaxOpra/filter3.php',
                        method: 'POST',
                        data: {
                            from_date: from_date,
                            to_date: to_date
                        },
                        success: function(data) {
                            $('.table-responsive').html(data);

                        }
                    });
                    $.ajax({
                        url: 'ajaxOpra/totalSales_itemCount.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            from_date: from_date,
                            to_date: to_date,
                        },
                        success: function(data) {

                            $('#totalSales').html("Total Amount: " + data.total);
                            $('#itemCount').html("Number of Items: " + data.row);
                            if (data.row == '0') {
                                $('#util').hide();
                            } else {
                                $('#util').show();
                            }
                            $('#content1').show();

                        }
                    })
                    $('#print').click(() => {
                        var from_date = $('#from_date').val();
                        var to_date = $('#to_date').val();
                        window.open('utilities/report_print/itemSalesReport_print.php?from_date=' + from_date + '&to_date=' + to_date, '_blank');

                    })
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
                            window.open('utilities/report_email/itemSalesReport_email.php?from_date=' + from_date + '&to_date=' + to_date + '&email=' + email, '_self');
                        }
                    })
                } else {
                    $('#d1_msz').show();
                    $('#d1_msz').delay(1500).fadeOut(500);
                    $('#content1').hide();
                    $('#util').hide();
                    $('.table-responsive').html("<p style='color:black'>Select date to get Item Sales Reports<span style='color:red;'>*</span></p>");
                }
            } else {
                $('#d_msz').show();
                $('#d_msz').delay(1500).fadeOut(500);
                $('#content1').hide();
                $('#util').hide();
                $('.table-responsive').html("<p style='color:black;'>Select date to get Item Sales Reports<span style='color:red;'>*</span></p>");
            }

        })
        $('#email_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['email_msg'] = '' ?>


    })
</script>