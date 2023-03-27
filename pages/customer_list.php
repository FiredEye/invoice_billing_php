<?php
session_start();
include('../inc/header.php');
include '../queries/Customer.php';
$customer = new Customer();
include '../queries/Invoice.php';
$invoice = new Invoice();
$customer->checkLoggedIn();
?>
<title>Invoice System</title>
<script src="../js/customer.js"></script>

<style>
    th,
    td {
        text-align: center;
    }


    .fa-solid.fa-print {

        font-size: 18px;
    }

    input[type="text"]::placeholder {


        text-align: center;
    }
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Customer-List Table:
            </h6>
            <a href="utilities/print_customer.php" class="btn btn-success float-right " title="Print Customers List" target="_blank">

                <i class="fa fa-print"></i>&nbsp;Print Customer/s

            </a>
        </div>
        <div class="card-body text-black">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Customer Id</th>
                            <th>Customer Name</th>
                            <th>Address</th>
                            <th>E-mail</th>
                            <th>Phone No.</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php
                        $customerList = $customer->getCustomerList();
                        foreach ($customerList as $customerDetails) {
                            $customerName = $customerDetails["first_name"] . " " . $customerDetails["last_name"];
                            echo '
              <tr>
                <td>' . $customerDetails["id"] . '</td>
                <td>' . $customerName . '</td>
                <td>' . $customerDetails["address"] . '</td>
                <td>' . $customerDetails["email"] . '</td>
                <td>' . $customerDetails["phone_no"] . '</td>
               
                <td><a href="edit_customer.php?update_id=' . $customerDetails["id"] . '"  title="Edit Customer"><i class="fa fa-edit text-warning"></i></a></td>
                <td><a href="#" id="' . $customerDetails["id"] . '" class="deleteCustomer' . $customerDetails["id"] . '"   title="Delete Customer"><i class="fa fa-trash text-danger"></i></a></td>
              </tr>
            '; ?>
                            <script>
                                $('.deleteCustomer' + <?php echo $customerDetails["id"]; ?>).click(() => {

                                    var id = $('.deleteCustomer' + <?php echo $customerDetails["id"]; ?>).attr("id");
                                    if (confirm("Are you sure you want to remove Customer ID: " + id + "?")) {
                                        $.ajax({
                                            url: "ajaxOpra/action1.php",
                                            method: "POST",
                                            data: {
                                                id: id,
                                                action: "delete_customer",
                                            },
                                            success: function(response) {
                                                if (response == 1) {
                                                    alert("Customer Removed.");
                                                    location.reload("customer_list.php");
                                                }
                                            },
                                        });
                                    } else {
                                        return false;
                                    }
                                })
                            </script>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include('../inc/footer.php'); ?>