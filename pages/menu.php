<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center bg-info" href="home.php" title="Invoice Management Software">
        <!-- rotate-n-15 -->
        <div class="sidebar-brand-img">
            <!-- <i class="fas fa-laugh-wink"></i> -->
            <img src="../img/logo_j.png" alt="logo" style="margin-left:-35px ;" class="img-fluid">
            <!-- <i class="fa-solid fa-warehouse"></i> -->
        </div>
        <!-- <div class="sidebar-brand-text text-white mx-2 pt-3">
            <p class="float-left">Inv</p><sup class="rotate-n-15 float-left pt-2">E</sup>
            <p class="float-left"> Ware</p>
        </div> -->

    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="home.php">
            <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider bg-white" />

    <!-- Heading -->
    <div class="sidebar-heading">Interface</div>

    <!-- Nav Item - Pages Collapse Menu -->
    <?php if ($_SESSION['role'] != 'Admin') { ?>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invoice" aria-expanded="true" aria-controls="invoice">
                <i class="fa fa-file-invoice-dollar"></i>
                <span>Invoice</span>
            </a>
            <div id="invoice" class="collapse" aria-labelledby="invoice" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Invoice Components:</h6>
                    <a class="collapse-item" href="create_invoice.php">Create Invoice</a>
                    <a class="collapse-item" href="invoice_list.php">Invoice List</a>
                </div>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#customer" aria-expanded="true" aria-controls="customer">
                <i class="fa fa-users"></i>
                <span>Customer</span>
            </a>
            <div id="customer" class="collapse" aria-labelledby="customer" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Customer Components:</h6>

                    <a class="collapse-item" href="add_customer.php">Add Customer</a>

                    <a class="collapse-item" href="customer_list.php">Customer List</a>

                </div>
            </div>
        </li>
    <?php } ?>
    <?php if ($_SESSION['role'] == 'Admin') { ?>


        <li class="nav-item">

            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#item" aria-expanded="true" aria-controls="item">
                <i class="fa fa-layer-group"></i>
                <span>Item</span>
            </a>
            <div id="item" class="collapse" aria-labelledby="item" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Item Components:</h6>
                    <a class="collapse-item" href="add_item.php">Add Item</a>
                    <a class="collapse-item" href="item_list.php">Item List</a>
                    <a class="collapse-item" href="fillItemQty.php">Fill Item Quantity</a>
                </div>
            </div>

        </li>
    <?php } ?>



    <!-- Divider -->
    <hr class="sidebar-divider bg-white" />

    <!-- Heading -->
    <div class="sidebar-heading">Records</div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#salesReport" aria-expanded="true" aria-controls="salesReport">
            <i class="fas fa-fw fa-folder"></i>
            <span>Sales Report</span>
        </a>
        <div id="salesReport" class="collapse" aria-labelledby="salesreport" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Sales Records:</h6>


                <a class="collapse-item" href="customerSales_report.php">Customer Sales Report</a>
                <a class="collapse-item" href="itemSales_report.php">Item Sales Report</a>
                <?php if ($_SESSION['role'] == 'Admin') { ?>
                    <a class="collapse-item" href="itemSalesbySP_report.php">Item Sold by S/P Report</a>
                <?php } ?>
            </div>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#invoiceReport" aria-expanded="true" aria-controls="invoiceReport">
            <!-- <i class="fa-solid fa-calculator"></i> -->
            <i class="fa fa-boxes"></i>
            <span>Invoice Report</span>
        </a>
        <div id="invoiceReport" class="collapse" aria-labelledby="invoicereport" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Invoice Records:</h6>
                <a class="collapse-item" href="invoice_report.php">Invoice Report</a>
                <a class="collapse-item" href="unpaid_report.php">Unpaid Report</a>

            </div>
        </div>
    </li>
    <?php if ($_SESSION['role'] == 'Admin') { ?>
        <li class="nav-item">
            <a class="nav-link" href="inventory_report.php">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Inventory Report</span></a>
        </li>
    <?php } ?>
    <!-- Nav Item - Charts -->
    <!-- <li class="nav-item">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Charts</span></a>
    </li>

        Nav Item - Tables -->
    <!-- <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li> -->

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block bg-white" />

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <!-- Main Content -->
    <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-info topbar mb-4 static-top shadow">
            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars text-dark"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle text-white" id="notify_bell" href="#" id="alertsDropdown" title="Notification" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>

                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter" id='badge' style="font-size:16px;"></span>

                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header text-center">Notifications</h6>
                        <div style="height:300px;" id="notify" class="overflow-auto">


                        </div>
                        <a class="dropdown-item text-center small text-gray-500" href="#"></a>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-white small"><?php
                                                                                if (isset($_SESSION['user'])) {
                                                                                    echo $_SESSION['user'];
                                                                                } ?></span>
                        <?php

                        $_POST['email'] = $_SESSION['email'];
                        $user = $invoice->loginUsers($_POST['email']); ?>
                        <img class="img-profile rounded-circle" style="object-fit:cover;" src="<?php echo $user[0]['image'] ?>" />
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-dark"></i>
                            Profile
                        </a>

                        <a class="dropdown-item" href="company.php">

                            <i class="fa fa-building fa-sm fa-fw mr-2 text-dark"></i>
                            Company Info.
                        </a>
                        <?php if ($user[0]['role'] == 'Admin') { ?>
                            <a class="dropdown-item" href="employee.php">
                                <i class="fa fa-users fa-sm fa-fw mr-2 text-dark"></i>
                                Employee list
                            </a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-dark"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <script>
            $(document).ready(() => {
                setInterval(() => {
                    $.ajax({
                        url: 'ajaxCall/notify_count.php',
                        success: (data) => {
                            if (data > 0) {
                                //alert(data);
                                $('#badge').html(data);

                            }
                            $.ajax({
                                url: 'ajaxCall/notification.php',
                                success: (result) => {
                                    $('#notify').html(result);
                                }
                            })

                        }
                    })
                }, 500);

                $('#notify_bell').click(() => {
                    $.ajax({
                        url: 'ajaxCall/read_message.php',
                        success: () => {
                            $('#badge').fadeOut();
                        }
                    })
                })
            })
        </script>