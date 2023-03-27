<?php
session_start();
include('../inc/header.php');
include '../queries/item.php';
$item = new Item();
$item->checkLoggedIn();
include '../queries/Invoice.php';
$invoice = new Invoice();

if (!empty($_GET['update_id']) && $_GET['update_id']) {
	$itemValues = $item->getItem($_GET['update_id']);
}
?>
<title>Invoice System</title>

<style>
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>
<div class="container-fluid" id="">

	<div class="card shadow mb-4 pb-1">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary float-left">
				Update Item Details:
			</h6>
		</div>
		<form action="" id="item-form" method="post" class="item-form" role="form" novalidate="">
			<div class="load-animate animated fadeInUp">

				<div class="row  mx-1" style="margin-top:40px;">
					<div class="table-responsive">
						<table class="table table-bordered text-black" id="itemTable" style="color:black;">
							<thead>
								<tr>
									<th width="12%">Item Code</th>
									<th width="27%">Item Name</th>
									<th width="24%">Price</th>
									<th width="21%">Quantity</th>
									<th width="16%">Min. Quantity</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" name="itemCode" class="form-control code" autocomplete="off" value="<?php echo $itemValues['code'] ?>" readonly></td>
									<td><input type="text" name="itemName" class="form-control name" autocomplete="off" value="<?php echo $itemValues['name'] ?>"></td>
									<td><input type="number" name="itemPrice" class="form-control price" autocomplete="off" value="<?php echo $itemValues['price'] ?>"></td>
									<td><input type="number" name="itemQuantity" class="form-control qty" autocomplete="off" value="<?php echo $itemValues['quantity'] ?>"></td>
									<td><input type="number" name="itemMinQuantity" class="form-control minQty" autocomplete="off" value="<?php echo $itemValues['min_quantity'] ?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row mt-3 mx-1">

					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-left:10%;margin-bottom:130px;">
						<input data-loading-text="Adding Item/s ..." type="submit" name="item_btn" value="Save Item/s" class="btn btn-success submit_btn" style="height:50px;width:150px;">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include('../inc/footer.php'); ?>
<script>
	$(document).ready(() => {
		var invalidChars = [
			"-", "+", "e", ".",
		];
		$('.name').keydown((e => {
			return /[a-z]/i.test(e.key);

		}));

		$('.code,.price,.qty,.minQty').keydown((e) => {
			if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
				e.preventDefault();

			}
		});
		$("#item-form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: 'ajaxCall/updateItem.php',
				method: 'POST',
				data: $('#item-form').serialize(),
				success: (data) => {
					if (data == 1) {
						alert('Item Updated.');
						location.replace('item_list.php');
					} else {
						alert(data);
					}

				}
			});
		});
	})
</script>