<?php
session_start();

include('../inc/header.php');
include('../queries/Customer.php');

$customer = new Customer();
include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>

<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Individual Customer Sales Report:(Payment)
            </h6>

            <div id="util">
                <a href="#" class="btn btn-success float-right" id="print" title="Print Customer Sales Report" target="_blank">
                    <i class="fa fa-print"></i>&nbsp;Print
                </a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#inventoryModal" class="btn float-right text-white mr-3" id="email" title=" Email Customer Sales Report" style="background-color:#F5761A;">
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
                <div class="col-sm-12 col-md-4" style="float:left;">
                    <div class="form-group">
                        <form action="" id="customerPay">
                            <select class="form-control" name="customerId" id="customerId" placeholder="Pick a Customer..." required>
                                <option value="">Select Customer...</option>
                                <?php
                                $data = $customer->getCustomerslist();
                                while ($row = mysqli_fetch_assoc($data)) {
                                ?>
                                    <option id="<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>">
                                        <?php echo $row['first_name'] . " " . $row['last_name'] . "(" . $row['phone_no'] . ")"; ?>
                                    </option>
                                <?php  } ?>
                            </select>

                    </div>
                </div>
                <div class="col-sm-12 col-md-5" style="float:left;" id="pay_div">
                    <input type="button" name="pay" id="pay" data-bs-toggle="modal" data-bs-target="#payModal" value="Make Payment" class="btn btn-primary " style="float:left;">
                </div>
            </div>

            <div class="row mb-2" id="content1" style="color: black;">
                <div class="col-sm-12 col-md-4 col-lg-4" id="rowSn"></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="totalSum"></div>
                <div class="col-sm-12 col-md-4 col-lg-4" id="dueSum"></div>
            </div>
            <hr class="divider d-md-block bg-dark" />
            <div class="table-responsive">
                <p style="color:black;"> Select Customer to get Customer Sales Report or to make payment<span style="color: red;">*</span></p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title emailName" id="inventoryModalLabel">Send Report To:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="recipient_name" class="col-form-label">Recipient Email-ID:</label>
                    <input type="email" class="form-control" id="recipient_name" placeholder="Enter Valid Email-ID" required autocomplete="off">
                </div>
                <div class="mb-3">
                    <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Please enter valid Email-ID</p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="send">Send</button>

            </div>
        </div>
    </div>

</div>
<div class="modal fade pay" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title payName" id="payModalLabel">Make Payment:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="recipient_name" class="col-form-label">Amount To be paid:</label>
                    <input type="number" class="form-control amt" name="payment_amt" id="payment_amt" placeholder="Enter Amount">
                </div>
                <div class="mb-3">
                    <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg1" style="color:white; border-radius:8px;float:left;font-size:18px;">Please enter valid Amount</p>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="customerPay" class="btn btn-primary" id="pay1">Pay</button>

            </div>
        </div>
    </div>

</div>
<?php include('../inc/footer.php'); ?>
<link href='../css/offline/jquery-ui.css' rel='stylesheet'>

<script src="../js/offline/jquery.min.js"></script>

<script src="../js/offline/jquery-ui.min.js">

</script>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" /> -->


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script> -->
<script src="../js/jquery.js"></script>
<script src="../js/offline/selectize.min.js"></script>
<link rel="stylesheet" href="../css/offline/selectize.bootstrap3.min.css">
<script>
    $(document).ready(() => {
        $('select').selectize({
            sortField: 'text'
        });
        $('#pay_div').hide();
        $('#util').hide();
        $('#errorMsg').hide();
        $('#errorMsg1').hide();
        $('#content1').hide();
        var invalidChars = [
            "-", "+", "e", "."
        ];


        $('.amt').keydown((e) => {
            if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
                e.preventDefault();

            }
        });
        $('#customerId').change(function() {
            var id = $('#customerId option:selected').val();
            // alert(id);


            $.ajax({
                url: 'ajaxOpra/fetch.php',
                method: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    $('.table-responsive').html(data);
                }
            }); //     url: 'ajaxCall/insert_payment.php',
            $.ajax({
                url: 'ajaxOpra/fetch1.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(data) {

                    if (data.due == 0) {
                        $('#pay_div').hide();
                        //console.log("if" + data.due);
                    } else {
                        $('#pay_div').show();
                        // console.log("else" + data.due);
                    }
                    $('#rowSn').html("No. of Invoices: " + data.row);
                    $('#totalSum').html("Total Invoice Amount: Rs." + data.total);
                    $('#dueSum').html("Total Due Amount: Rs." + data.due);
                    $('.emailName').html("Send Report To:(" + data.name + ")");
                    $('.payName').html("Make Payment:(" + data.name + ")");
                    $('#payment_amt').val(data.due);
                    $('#recipient_name').val(data.email);
                    $('#content1').show();
                    $('#util').show();
                }
            });
        });
        $("#customerPay").submit(function(e) {
            e.preventDefault();
            if ($('#payment_amt').val() <= 0 || $('#payment_amt').val() == '') {
                $('#errorMsg1').show();
                $('#errorMsg1').delay(1500).fadeOut(500);
            } else {
                $.ajax({
                    url: 'ajaxCall/insert_payment.php',
                    method: 'POST',
                    data: $('#customerPay').serialize(),
                    success: function(data) {
                        if (data == 1) {
                            alert("Payment Accepted.");
                            location.replace('payment.php');
                        } else {
                            alert(data)
                        }

                    }
                })
            }
        });




        $('#print').click(() => {
            var customer_id = $('#customerId').val();
            window.open('utilities/report_print/individualCustSalesReport_print.php?cust_id=' + customer_id, '_blank');

        })
        $('#send').click(() => {
            var customer_id = $('#customerId').val();
            var email = $('#recipient_name').val();
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var result = re.test(email);
            if (email == '' || result == 0) {
                $('#errorMsg').show();
                $('#errorMsg').delay(1500).fadeOut(500);
            } else {
                window.open('utilities/report_email/individualCustSalesReport_email.php?cust_id=' + customer_id + '&email=' + email, '_self');
            }
        })




        $('#email_msg').delay(2000).fadeOut(700);
        <?php $_SESSION['email_msg'] = '' ?>


    })
</script>