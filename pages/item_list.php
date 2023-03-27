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
<script src="../js/item.js"></script>

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
                Item-List Table:
            </h6>
            <a href="utilities/print_item.php" class="btn btn-success float-right " title="Print Item List" target="_blank">

                <i class="fa fa-print"></i>&nbsp;Print Item/s

            </a>
        </div>
        <div class="card-body text-black">
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-black" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-dark">
                        <tr style="font-size:13px ;">
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Min Quantity</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php
                        $itemList = $item->getItemlist();
                        foreach ($itemList as $itemDetails) {
                            echo '
              <tr>
                <td>' . $itemDetails["code"] . '</td>
                <td>' . $itemDetails["name"] . '</td>
                <td>' . $itemDetails["price"] . '</td>
                <td>' . $itemDetails["quantity"] . '</td>
                <td>' . $itemDetails["min_quantity"] . '</td>
               
                <td><a href="edit_item.php?update_id=' . $itemDetails["code"] . '"  title="Edit Item"><i class="fa fa-edit text-warning"></i></a></td>
                <td><a href="#" id="' . $itemDetails["code"] . '" class="deleteItem' . $itemDetails["code"] . '"  title="Delete Item"><i class="fa fa-trash text-danger"></i></a></td>
              </tr>
            '; ?>
                            <script>
                                $('.deleteItem' + <?php echo $itemDetails["code"]; ?>).click(() => {

                                    var id = $('.deleteItem' + <?php echo $itemDetails["code"]; ?>).attr("id");
                                    if (confirm("Are you sure you want to remove Item ID: " + id + "?")) {
                                        $.ajax({
                                            url: "ajaxOpra/action2.php",
                                            method: "POST",
                                            data: {
                                                id: id,
                                                action: "delete_item",
                                            },
                                            success: function(response) {
                                                if (response == 1) {
                                                    alert("Item deleted.");
                                                    location.reload("item_list.php");
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