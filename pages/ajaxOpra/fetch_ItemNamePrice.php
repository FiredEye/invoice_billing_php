<?php
include '../../queries/item.php';
$item = new Item();
if (isset($_POST['code']) && $_POST['ajax'] == 1) {
    $data = $item->getNamePrice($_POST['code']);

    echo json_encode($data);
} else {
    echo "no result";
}
