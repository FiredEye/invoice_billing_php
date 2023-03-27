<?php
session_start();
include('../inc/header.php');
include '../queries/item.php';
include '../queries/Invoice.php';
include '../queries/Customer.php';

$customer = new Customer();
$item = new Item();
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>
<title>Invoice System</title>
<script src="../js/invoice.js"></script>
<!-- <link href="../css/home.css" rel="stylesheet"> -->
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
		<form action="" id="invoice-form" method="post" class="invoice-form" role="form" novalidate="">
			<div class="load-animate animated fadeInUp">
				<input id="currency" type="hidden" value="$">
				<div class="row p-0 m-0">
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 rounded" style="background-color: lightblue; color:black;">
						<h3>From,</h3>
						<?php echo $_SESSION['user']; ?><br>
						<?php echo $_SESSION['address']; ?><br>
						<?php echo $_SESSION['mobile']; ?><br>
						<?php echo $_SESSION['email']; ?><br>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">

					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4  pull-right rounded" style="background-color:#FFE5B4;color:black;">
						<h3>To,</h3>
						<form method="POST">
							<div class="form-group">

								<select class="form-control" name="customerId" placeholder="Pick a Customer..." required>
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
							<!-- <div class="form-group" id="customerAddress">
								<textarea class="form-control" rows="3" name="address" id="address" placeholder="Your Address" readonly></textarea>
							</div> -->
						</form>

					</div>
				</div>
				<hr class="divider d-md-block bg-dark" />

				<div class="row mt-3 mx-1">
					<div class="table-responsive">
						<table class="table table-bordered text-black" id="invoiceItem" width="100%" cellspacing="0" cellpadding="0">
							<tr height="5px">
								<th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
								<th width="15%">Item No</th>
								<th width="38%">Item Name</th>
								<th width="15%">Quantity</th>
								<th width="15%">Price</th>
								<th width="15%">Total</th>
							</tr>
							<tr>
								<td><input class="itemRow" type="checkbox"></td>
								<td><select class="form-control" name="productCode[]" id="productCode_1" placeholder="Pick Item Code..." required>
										<option value="" disabled selected>Select Customer...</option>
										<?php
										$data = $item->getItemslist();
										while ($row = mysqli_fetch_assoc($data)) {
										?>
											<option id="<?php echo $row['code']; ?>" value="<?php echo $row['code']; ?>">
												<?php echo $row['code']; ?>
											</option>
										<?php  } ?>
									</select>
								</td>
								<td><input type="text" name="productName[]" id="productName_1" class="form-control" autocomplete="off" readonly></td>
								<!-- oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '');" -->
								<td><input type="number" name="quantity[]" id="quantity_1" class="form-control quantity" autocomplete="off"></td>
								<td><input type="number" name="price[]" id="price_1" class="form-control price" autocomplete="off" readonly></td>
								<td><input type="number" name="total[]" id="total_1" class="form-control total" autocomplete="off" readonly></td>
							</tr>
						</table>
					</div>
				</div>

				<div class="row mx-1">
					<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
						<button class="btn btn-danger delete" id="removeRows" type="button">- Delete</button>
						<button class="btn btn-success" id="addRows" type="button">+ Add More</button>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<p class="bg-danger ms-1 px-2 mt-1" id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Minimum Qty should be 1 or More than 1 </p>
						<p class="bg-danger ms-1 px-2 mt-1" id="errorMsg2" style="color:white; border-radius:8px;float:left;font-size:18px;">Please refill the item, the level is low or is out of stock</p>
					</div>
				</div>
				<div class="row mx-1">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

						<br>
						<div class="form-group" style="float:left;">
							<input type="hidden" value="<?php echo $_SESSION['userid']; ?>" class="form-control" name="recordedBy">
							<input data-loading-text="Saving Invoice..." type="submit" name="invoice_btn" value="Save Invoice" class="btn btn-success submit_btn invoice-save-btm">
						</div>
						<div class="form-group" style="float:left;color:black;">
							<b style="margin-left:50px;">Due Date:</b> <br>
							<input type="text" name="due_date" id="due_date" class="form-control" placeholder="Enter Due Date" style="margin:15px 0 0 50px;" autocomplete="off">
						</div>
						<p class="bg-danger ms-1 px-2 mt-1" id="errorMsg1" style="color:white; border-radius:8px;float:left;font-size:18px;">Amount paid cannot be more than Total Amount Or Negative </p>

					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="color:black;">

						<!-- <span class="form-inline bg-primary"> -->
						<div class="form-group form-inline float-right">
							<label>Subtotal: &nbsp;</label>
							<div class="input-group">
								<span class="input-group-text" id="basic-addon">Rs.</span>
								<input value="" type="number" class="form-control" name="subTotal" id="subTotal" placeholder="Subtotal" readonly>
							</div>
						</div>

						<div class="form-group form-inline float-right">
							<label>Tax Rate: &nbsp;</label>
							<div class="input-group">
								<input value="" type="number" class="form-control" name="taxRate" id="taxRate" placeholder="Tax Rate">
								<span class="input-group-text" id="basic-addon">%</span>
							</div>
						</div>
						<div class="form-group form-inline float-right">
							<label>Tax Amount: &nbsp;</label>
							<div class="input-group">
								<span class="input-group-text" id="basic-addon">Rs.</span>
								<input value="" type="number" class="form-control" name="taxAmount" id="taxAmount" placeholder="Tax Amount" readonly>
							</div>
						</div>
						<div class="form-group form-inline float-right">
							<label>Total:(Round off) &nbsp;</label>
							<div class="input-group">
								<span class="input-group-text" id="basic-addon">Rs.</span>
								<input value="" type="number" class="form-control" name="totalAftertax" id="totalAftertax" placeholder="Total" readonly>
							</div>
						</div>
						<div class="form-group form-inline float-right">
							<label>Amount Paid: &nbsp;</label>
							<div class="input-group">
								<span class="input-group-text" id="basic-addon">Rs.</span>
								<input value="" type="number" class="form-control paid" name="amountPaid" id="amountPaid" placeholder="Amount Paid">
							</div>
						</div>
						<div class="form-group form-inline float-right">
							<label>Amount Due: &nbsp;</label>
							<div class="input-group">
								<span class="input-group-text" id="basic-addon">Rs.</span>
								<input value="" type="number" class="form-control" name="amountDue" id="amountDue" placeholder="Amount Due" readonly>
							</div>
						</div>
						<!-- </span> -->
					</div>

				</div>
				<div class="clearfix"></div>
			</div>
		</form>

	</div>

</div>
<?php include('../inc/footer.php'); ?>
<!-- <script src="../js/jquery.js"></script> -->
<!-- 
 -->


<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" /> -->
<!-- <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js">
</script> -->
<link rel="stylesheet" href="../css/offline/selectize.bootstrap3.min.css">
<link rel="stylesheet" href="../css/offline/1.12.1theme-ui-light-jquery-ui.css">
<script src="../js/offline/3.4.1jquery.min.js"></script>
<script src="../js/offline/selectize.min.js"></script>
<script src="../js/offline/1.12.1jquery-ui.min.js"></script>
<!-- <script src="../js/offline/jquery.js"></script> -->
<script>
	$(document).ready(function() {
		var invalidChars = [
			"-", "+", "e", ".",
		];
		$('.quantity,.paid').keydown((e) => {
			if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
				e.preventDefault();
			}
		});
		var invalidChar = [
			"-", "+", "e",
		];
		$('#taxRate').keydown((e) => {
			if (invalidChar.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
				e.preventDefault();
			}
		});
		$('#errorMsg').hide();
		$('#errorMsg1').hide();
		$('#errorMsg2').hide();


		$("#invoice-form").submit(function(e) {

			e.preventDefault();

			$.ajax({
				url: 'ajaxCall/insertInvoice.php',
				method: 'POST',
				data: $('#invoice-form').serialize(),
				success: (data) => {
					if (data == 1) {
						alert('Invoice Inserted');
						location.replace('invoice_list.php');
					} else {
						alert(data);
					}

				}
			});
		});
		$('select').selectize({
			sortField: 'text'
		});


		var count = $(".itemRow").length;

		var no = 1;
		$('#addRows').click(() => {
			var quantity = $('#quantity_' + no).val();

			if (quantity == '' || quantity <= 0) {
				$('#errorMsg').show();
				$('#errorMsg').delay(1500).fadeOut(500);
			} else {
				no++;
				count++;
				var htmlRows = '';
				htmlRows += '<tr>';
				htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
				htmlRows += '<td><select class="form-control" name="productCode[]" id="productCode_' + count + '" placeholder="Pick Item Code..." required>' +
					'<option value=""disabled selected>Select Customer...</option>' +
					'<?php '+
									';
						$data = $item->getItemslist();
						'+
									';
						while ($row = mysqli_fetch_assoc($data)) {
							'+
									' ?>' +
					'<option id="<?php echo $row['code']; ?>" value="<?php echo $row['code']; ?>">' +
					'<?php echo $row['code']; ?>' +
					'</option>' +
					'<?php  } ?>' +
					'</select>' +
					'</td>';

				htmlRows += '<td><input type="text" name="productName[]" id="productName_' + count + '" class="form-control" autocomplete="off" readonly></td>';
				htmlRows += '<td><input type="number" name="quantity[]" id="quantity_' + count + '" class="form-control quantity" autocomplete="off"></td>';
				htmlRows += '<td><input type="number" name="price[]" id="price_' + count + '" class="form-control price" autocomplete="off" readonly></td>';
				htmlRows += '<td><input type="number" name="total[]" id="total_' + count + '" class="form-control total" autocomplete="off" readonly></td>';
				htmlRows += '</tr>';
				$('#invoiceItem').append(htmlRows);
				var invalidChars = [
					"-", "+", "e", ".",
				];
				$('.quantity').keydown((e) => {
					if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
						e.preventDefault();
					}
				})

			}
			$('select').selectize({
				sortField: 'text'
			});
			$('#productCode_' + count).change(function() {
				var code = $('#productCode_' + count + ' option:selected').val();
				$.ajax({
					url: 'ajaxOpra/fetch_ItemNamePrice.php',
					method: 'POST',
					dataType: 'json',
					data: {
						ajax: 1,
						code: code
					},
					success: function(data) {

						if (parseInt(data.quantity) <= parseInt(data.min_quantity)) {
							$('#errorMsg2').show();
							$('#errorMsg2').delay(1500).fadeOut(500);
							$('#productName_' + count).css("color", "red");
							$('#price_' + count).css("color", "red");
							$('#productName_' + count).val(data.name);
							$('#price_' + count).val(data.price);
						} else {
							$('#productName_' + count).css("color", "black");
							$('#price_' + count).css("color", "black");
							$('#productName_' + count).val(data.name);
							$('#price_' + count).val(data.price);
						}
					}
				});

			});
		});

		// $('#customerName').change(function() {
		// 	var name = $('#customerName option:selected').val();

		// 	const narr = name.split(" ");
		// 	var fname = narr[0];
		// 	var lname = narr[1];
		// 	$.ajax({
		// 		url: 'ajaxOpra/fetch.php',
		// 		method: 'POST',
		// 		data: {
		// 			ajax: 1,
		// 			fname: fname,
		// 			lname: lname
		// 		},
		// 		success: function(response) {


		// 			$('#address').text(response);
		// 		}
		// 	});
		// });

		$('#productCode_1').change(function() {
			var code = $('#productCode_1 option:selected').val();
			$.ajax({
				url: 'ajaxOpra/fetch_ItemNamePrice.php',
				method: 'POST',
				dataType: 'json',
				data: {
					ajax: 1,
					code: code
				},
				success: function(data) {

					if (parseInt(data.quantity) <= parseInt(data.min_quantity)) {
						$('#errorMsg2').show();
						$('#errorMsg2').delay(1500).fadeOut(500);
						$('#productName_1').css("color", "red");
						$('#price_1').css("color", "red");
						$('#productName_1').val(data.name);
						$('#price_1').val(data.price);
					} else {
						$('#productName_1').css("color", "black");
						$('#price_1').css("color", "black");
						$('#productName_1').val(data.name);
						$('#price_1').val(data.price);
					}



				}
			});
		});



		$(document).on('click', '#removeRows', function() {
			$(".itemRow:checked").each(function() {
				$(this).closest('tr').remove();
			});
			$('#checkAll').prop('checked', false);
			calculateTotal();
		});
		$.datepicker.setDefaults({
			dateFormat: 'yy-mm-dd'
		});
		$('#due_date').datepicker({
			minDate: 0
		});



		$('#amountPaid').keyup(() => {

			if (parseInt($('#amountPaid').val()) > parseInt($('#totalAftertax').val()) || parseInt($('#amountPaid').val()) < 0) {
				$('#errorMsg1').show();
				$('#errorMsg1').delay(1500).fadeOut(500);

			}




		});



	})
</script>