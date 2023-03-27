<?php
session_start();
include('../inc/header.php');

include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();

?>

<style>
    a {
        text-decoration: none;
    }

    a:hover {
        text-decoration: none;
    }
</style>
<title>Invoice System</title>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <!-- <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

    </div> -->

    <!-- Content Row -->
    <div class="row">
        <?php if ($_SESSION['role'] != 'Admin') { ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <a href='payment.php' class="h6 mb-0 font-weight-bold text-black">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center mb-0">

                                <div class="col mr-2">

                                    <div class="text-s font-weight-bold text-success text-uppercase mb-1">
                                        Add Payment
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-black">
                                        <span class='text-s'>(view individual invoice list)</span>
                                    </div>

                                </div>
                                <div class="col-auto">
                                    <i class="fa fa-2x fa-dollar-sign text-gray-600"></i>

                                </div>

                            </div>
                            <!-- <hr class="divider d-md-block bg-dark" /> -->
                            <!-- <a href='payment.php' class="h6 mb-0 font-weight-bold text-black">
                        More info <i class="fa fa-arrow-right"></i>
                    </a> -->
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
        <div class="col-xl-4 col-md-6 mb-4">
            <a href='#' class="h6 mb-0 font-weight-bold text-black invoiceLink">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center mb-0">
                            <div class="col mr-2">
                                <div class="text-s font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Invoices
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800 dinvoice">

                                </div>

                            </div>

                            <div class="col-auto">
                                <i class="fa fa-2x fa-file-invoice-dollar text-gray-600"></i>

                            </div>

                        </div>
                        <!-- <hr class="divider d-md-block bg-dark" />
                    <a href='#' class="h6 mb-0 font-weight-bold text-black invoiceLink">
                        More info <i class="fa fa-arrow-right"></i> (View Invoices)
                    </a> -->
                    </div>
                </div>
            </a>
        </div>
        <?php if ($_SESSION['role'] == 'Admin') { ?>
            <div class="col-xl-4 col-md-6 mb-4">
                <a href='#' class="h6 mb-0 font-weight-bold text-black stockLink">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center mb-0">
                                <div class="col mr-2">
                                    <div class="text-s font-weight-bold text-danger text-uppercase mb-1">
                                        Low Stock
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800 lstock">

                                    </div>

                                </div>

                                <div class="col-auto">
                                    <i class="fa fa-2x fa-layer-group text-gray-600"></i>
                                </div>

                            </div>
                            <!-- <hr class="divider d-md-block bg-dark" />
                    <a href='#' class="h6 mb-0 font-weight-bold text-black stockLink">
                        More info <i class="fa fa-arrow-right"></i> (Fill Stock)
                    </a> -->
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>

    <!-- Content Row -->

    <div class="row">
        <!-- Area Chart -->
        <div class="col-xl-5 col-lg-6">
            <div class="card border-dark shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Today's daily activities: (<?php
                                                    date_default_timezone_set('Asia/Kathmandu');
                                                    echo date('Y-m-d') ?>)
                    </h6>

                </div>
                <!-- Card Body -->
                <div class="card-body text-black">
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0">No. of Invoices:</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="mb-0 invoice_row"></p>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row">
                        <div class="col-sm-5">
                            <p class="mb-0">Total Sales amount:</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="mb-0 total_sales"></p>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row text-success">
                        <div class="col-sm-5">
                            <p class="mb-0">Cash Payment:</p>
                        </div>
                        <div class="col-sm-7">
                            <p class="mb-0 float-left cash_pay"></p>
                        </div>
                    </div>
                    <hr class="divider d-md-block bg-dark" />
                    <div class="row text-danger">
                        <div class="col-sm-5">
                            <p class="mb-0">Due Amount:</p>
                        </div>
                        <div class="col-sm-7">

                            <p class="mb-0 float-left due"></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(() => {
        setInterval(() => {
            $.ajax({
                url: 'ajaxCall/dailyInvoice.php',
                dataType: 'json',
                success: (result) => {
                    $('.invoice_row').html(result[0]);
                    $('.total_sales').html('Rs.' + result[1]);
                    $('.cash_pay').html('Rs.' + result[2]);
                    $('.due').html('Rs.' + result[3]);
                }
            });
            $.ajax({
                url: 'ajaxCall/pendingInvo&lowStockCount.php',
                dataType: 'json',
                success: (result) => {
                    $('.dinvoice').html(result.dueInvoice);
                    $('.lstock').html(result.lowStock);

                }
            })
        }, 500);
        $('.stockLink').click(() => {
            if ($('.lstock').html() == '0') {
                alert("No low stock Items available.")
            } else {
                window.open('viewLowStock.php', '_self');
            }
        });
        $('.invoiceLink').click(() => {
            if ($('.dinvoice').html() == '0') {
                alert("No Pending Ivoices available.")
            } else {
                window.open('viewPendingInvoice.php', '_self');
            }
        })
    })
</script>