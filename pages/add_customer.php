<?php
session_start();
include('../inc/header.php');
include '../queries/Customer.php';
$customer = new Customer();
$customer->checkLoggedIn();
include '../queries/Invoice.php';
$invoice = new Invoice();
?>
<title>Invoice System</title>
<script src="../js/customer.js"></script>

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
				Add Customers:
			</h6>

			<p class="bg-danger ms-5 px-2 " id="errorMsg" style="color:white; border-radius:8px;float:left;font-size:18px;">Fill all the fields properly! </p>

			<input type="submit" name="customer_btn" form="customer-form" value="Save Customer/s" class="btn btn-success float-right " style="height:40px;width:150px;">

		</div>
		<form action="" id="customer-form" method="post" class="customer-form" role="form" novalidate="">
			<div class="load-animate animated fadeInUp">

				<div class="row mt-4 mx-1">
					<div class="table-responsive">
						<table class="table table-bordered text-black" id="customerTable" style="color:black;">
							<tr>
								<th width="2%"><input id="checkAll" class="formcontrol" type="checkbox"></th>
								<th width="12%">First Name</th>
								<th width="16%">Last Name</th>
								<th width="30%">Customer Address</th>
								<th width="16%">Phone No.</th>
								<th width="24%">E-mail</th>
							</tr>
							<tr>
								<td><input class="itemRow" type="checkbox"></td>
								<td><input type="text" name="customerFName[]" id="customerFName_1" class="form-control fname" autocomplete="off" required maxlength="10"></td>
								<td><input type="text" name="customerLName[]" id="customerLName_1" class="form-control lname" autocomplete="off" required></td>
								<td><input type="text" name="customerAddress[]" id="customerAddress_1" class="form-control address" autocomplete="off" reqired></td>
								<td><input type="number" name="customerPhone[]" id="customerPhone_1" class="form-control number" autocomplete="off" required maxlength="10"></td>
								<td><input type="email" name="customerEmail[]" id="customerEmail_1" class="form-control" autocomplete="off" required></td>
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
<script src="../js/offline/jquery.js"></script>
<script>
	$(document).ready(function() {
		var invalidChars = [
			"-", "+", "e", ".",
		];
		$('.fname,.lname,.address').keydown((e => {
			return /[a-z]/i.test(e.key);

		}));

		$('.number').keydown((e) => {
			if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
				e.preventDefault();

			}
		});
		$('.number').on('input', function() {
			if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
		});
		$("#errorMsg").hide();
		$("#customer-form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: 'ajaxCall/insertCustomer.php',
				method: 'POST',
				data: $('#customer-form').serialize(),
				success: (data) => {
					if (data == 1) {
						alert('Customer/s Inserted.');
						location.replace('customer_list.php');
					} else {
						alert(data);
					}

				}
			});
		});
		var count = $(".itemRow").length;
		var no = 1;
		$('#addRows').click(() => {
			var fname = $("#customerFName_" + no).val();
			var lname = $("#customerLName_" + no).val();
			var address = $("#customerAddress_" + no).val();
			var phone = $("#customerPhone_" + no).val();
			var email = $("#customerEmail_" + no).val();
			if (fname == '' || lname == '' || address == '' || phone == '' || email == '' || phone < 0) {
				$("#errorMsg").show();
				$("#errorMsg").delay(1500).fadeOut(500);
			} else {
				no++;
				count++;
				var htmlRows = "";
				htmlRows += "<tr>";
				htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
				htmlRows +=
					'<td><input  class="form-control fname"  type="text" name="customerFName[]"  id="customerFName_' + count + '" autocomplete="off"></td>';
				htmlRows +=
					'<td><input  class="form-control lname"  type="text" name="customerLName[]"  id="customerLName_' + count + '" autocomplete="off"></td>';
				htmlRows +=
					'<td><input  class="form-control address"  type="text" name="customerAddress[]" id="customerAddress_' + count + '" autocomplete="off"></td>';
				htmlRows +=
					'<td><input  class="form-control number"  type="number" name="customerPhone[]" id="customerPhone_' + count + '" autocomplete="off" maxlength="10"></td>';
				htmlRows +=
					'<td><input  class="form-control email"  type="email" name="customerEmail[]" id="customerEmail_' + count + '" autocomplete="off"></td>';
				htmlRows += "</tr>";
				$("#customerTable").append(htmlRows);
				var invalidChars = [
					"-", "+", "e", ".",
				];
				$('.fname,.lname,.address').keydown((e => {
					return /[a-z]/i.test(e.key);

				}));


				$('.number').keydown((e) => {
					if (invalidChars.includes(e.key) || e.keyCode === 40 || e.keyCode === 38) {
						e.preventDefault();

					}
				});
				$('.number').on('input', function() {
					if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);
				});
			}

		});



	})
</script>