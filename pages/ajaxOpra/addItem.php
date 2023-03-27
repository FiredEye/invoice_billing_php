<?php
session_start();
include '../../queries/item.php';
include '../../queries/Invoice.php';
$invoice = new Invoice();
$item = new Item();
$result = $item->addItem($_GET['code'], $_GET['quantity']);
if ($result) {
    if ($_GET['LS'] == 'ls') {
?>
        <script>
            alert("Quantity added sucessfully");
            location.replace('../viewLowStock.php');
        </script>
    <?php } else {

    ?><script>
            alert("Quantity added sucessfully");
            location.replace('../fillItemQty.php');

            <?php } ?>
        </script>
    <?php
}
