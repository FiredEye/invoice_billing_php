<?php
session_start();
include('../inc/header.php');
include '../queries/item.php';
$item = new Item();
$item->checkLoggedIn();
include '../queries/Invoice.php';
$invoice = new Invoice();

?>
<title>Invoice System</title>
<script src="../js/item.js"></script>

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

	<div class="card shadow mb-4 pb-3">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary float-left">
				Add Items:
			</h6>

			<p class="bg-danger ms-5 px-2" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Fill all the fields properly! </p>
			<input type="submit" name="item_btn" form="item-form" value="Save Items/s" class="btn btn-success float-right " style="height:40px;width:150px;">

		</div>
		<form action="" id="item-form" method="post" class="item-form" role="form" novalidate="">
			<div class="load-animate animated fadeInUp">
				<div class="row mt-4 mx-1">
					<div class="table-responsive">
						<table class="table table-bordered" id="itemTable" style="color:black;">
							<tr>
								<th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
								<th width="12%">Item Code</th>
								<th width="27%">Item Name</th>
								<th width="24%">Price</th>
								<th width="21%">Quantity</th>
								<th width="14%">Min. Quantity</th>
							</tr>
							<tr>
								<td><input class="itemRow" type="checkbox"></td>
								<td><input type="number" name="itemCode[]" id="itemCode_1" class="form-control code" autocomplete="off"></td>
								<td><input type="text" name="itemName[]" id="itemName_1" class="form-control name" autocomplete="off"></td>
								<td><input type="number" name="itemPrice[]" id="itemPrice_1" class="form-control price" autocomplete="off"></td>
								<td><input type="number" name="itemQuantity[]" id="itemQuantity_1" class="form-control qty" autocomplete="off"></td>
								<td><input type="number" name="itemMinQuantity[]" id="itemMinQuantity_1" class="form-control minQty" autocomplete="off"></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="row mx-1">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
						<button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
						<button class="btn btn-success" id="addRows" type="button">+ Add More</button>
					</div>
				</div>

			</div>
		</form>
	</div>
</div>
<?php include('../inc/footer.php'); ?>

<script>
	$(document).ready(function() {
		$("#errorMsg").hide();
		var invalidChars = [
			"-", "+", "e", ".",
		];
		$('.name').keydown((e => {
			return /[a-z]/i.test(e.key);
			//var regex = new RegExp("^[ a-zA-Z -]*$");

		}));

		$('.code,.price,.qty,.minQty').keydown((e) => {
			if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
				e.preventDefault();

			}
		});

		$("#item-form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: 'ajaxCall/insertItem.php',
				method: 'POST',
				data: $('#item-form').serialize(),
				success: (data) => {
					if (data == 1) {
						alert('Item/s Inserted.');
						location.replace('item_list.php');
					} else {
						alert(data);
					}

				}
			});
		});
		var count = $(".itemRow").length;
		var no = 1;
		$('#addRows').click(() => {
			var code = $("#itemCode_" + no).val();
			var name = $("#itemName_" + no).val();
			var price = $("#itemPrice_" + no).val();
			var quantity = $("#itemQuantity_" + no).val();
			var minquantity = $("#itemMinQuantity_" + no).val();
			if (code == '' || name == '' || price == '' || quantity == '' || quantity < 0 || minquantity == '' || minquantity < 0 || price < 0 || code < 0) {
				$("#errorMsg").show();
				$("#errorMsg").delay(1500).fadeOut(500);
			} else {
				no++;
				count++;
				var htmlRows = '';
				htmlRows += '<tr>';
				htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
				htmlRows += '<td><input type="number" name="itemCode[]" id="itemCode_' + count + '" class="form-control code" autocomplete="off"></td>';
				htmlRows += '<td><input type="text" name="itemName[]" id="itemName_' + count + '" class="form-control name" autocomplete="off"></td>';
				htmlRows += '<td><input type="number" name="itemPrice[]" id="itemPrice_' + count + '" class="form-control price" autocomplete="off"></td>';
				htmlRows += '<td><input type="number" name="itemQuantity[]" id="itemQuantity_' + count + '" class="form-control qty" autocomplete="off"></td>';
				htmlRows += '<td><input type="number" name="itemMinQuantity[]" id="itemMinQuantity_' + count + '" class="form-control minQty" autocomplete="off"></td>';
				htmlRows += '</tr>';
				$('#itemTable').append(htmlRows);
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
			}
		});


	})
</script>