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
    table {
        font-family: Arial, Helvetica, sans-serif;
    }

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

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary float-left">
                Item-List Table:(Add Item Quantity)
            </h6>

        </div>
        <div class="card-body text-black">
            <div class="table-responsive">
                <table class='table table-bordered table-sm text-black' width='100%' cellspacing='0'>
                    <thead class='thead-dark'>
                        <tr style='font-size:13px;'>
                            <th>Item Code</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Item Quantity</th>
                            <th>Minimum Quantity</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody style="font-size:15px;">
                        <?php
                        $itemList = $item->getLowStock();
                        $lowStock = mysqli_num_rows($itemList);
                        if ($lowStock == 0) {
                            echo ' <tr>
                            <td colspan="9">No Data Found</td>
                        </tr>';
                        } else {
                            foreach ($itemList as $itemDetails) {

                                echo '
              <tr>
                <td>' . $itemDetails["code"] . '</td>
                <td>' . $itemDetails["name"] . '</td>
                <td>' . $itemDetails["price"] . '</td>
                <td>' . $itemDetails["quantity"] . '</td>
                <td>' . $itemDetails["min_quantity"] . '</td>    
                          
                <td><button class="btn btn-primary" id="addItem' . $itemDetails["code"] . '" data-bs-toggle="modal" data-bs-target="#addModal">Add</button></td>
              </tr>
            '; ?>
                                <script>
                                    $('#addItem' + <?php echo $itemDetails["code"]; ?>).click(() => {

                                        $('#id').val(<?php echo $itemDetails["code"]; ?>);
                                        $('.itemName').html('Add Item Quantity:(<?php echo $itemDetails["name"]; ?>)');
                                    })
                                </script>

                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-black">
            <div class="modal-header">
                <h5 class="modal-title itemName" id="addModalLabel">Add Item Quantity:</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="num" class="col-form-label ">Add Quantity:</label>
                    <input type="hidden" class="form-control" id="id">
                    <input type="number" class="form-control qty" name="number" id="num" autocomplete="off">
                </div>


                <div class="mb-3">
                    <p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Enter valid Quantity</p>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="send">Add</button>

            </div>
        </div>
    </div>
</div>
<?php include('../inc/footer.php'); ?>
<script>
    $(document).ready(() => {
        $('#errorMsg').hide();
        var invalidChars = [
            "-", "+", "e", ".",
        ];
        $('.qty').keydown((e) => {
            if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
                e.preventDefault();
            }

        });

        $('#send').click(() => {
            var quantity = $('#num').val();
            var code = $('#id').val();
            if ($('#num').val() == '' || $('#num').val() <= 0) {
                $('#errorMsg').show();
                $('#errorMsg').delay(1500).fadeOut(500);
            } else {
                window.open('ajaxOpra/addItem.php?LS=ls&code=' + code + '&quantity=' + quantity, '_self');
            }
        })



    })
</script>