<?php
session_start();
include('../inc/header.php');
include '../queries/Invoice.php';
$invoice = new Invoice();
include '../queries/Customer.php';
$customer = new Customer();
$customer->checkLoggedIn();
if (!empty($_GET['update_id']) && $_GET['update_id']) {
	$cutomerValues = $customer->getCustomer($_GET['update_id']);
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
				Update Customer Details:
			</h6>
		</div>
		<form action="" id="customer-form" method="post" class="customer-form" role="form" novalidate="">
			<div class="load-animate animated fadeInUp">

				<div class="row  mx-1" style="margin-top:40px;">
					<div class="table-responsive">
						<table class="table table-bordered text-black" id="customerTable" style="color:black;">
							<thead>
								<tr>
									<th width="10%">Id</th>
									<th width="12%">First Name</th>
									<th width="14%">Last Name</th>
									<th width="21%">Customer Address</th>
									<th width="14%">Phone No.</th>
									<th width="29%">E-mail</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" name="id" value="<?php echo $cutomerValues['id']  ?>" class="form-control" readonly></td>
									<td><input type="text" name="customerFName" class="form-control fname" autocomplete="off" value="<?php echo $cutomerValues['first_name'] ?>"></td>
									<td><input type="text" name="customerLName" class="form-control lname" autocomplete="off" value="<?php echo $cutomerValues['last_name'] ?>"></td>
									<td><input type="text" name="customerAddress" class="form-control address" autocomplete="off" value="<?php echo $cutomerValues['address'] ?>"></td>
									<td><input type="number" name="customerPhone" class="form-control number" autocomplete="off" value="<?php echo $cutomerValues['phone_no'] ?>" maxlength="10"></td>
									<td><input type="email" name="customerEmail" class="form-control" autocomplete="off" value="<?php echo $cutomerValues['email'] ?>"></td>
								</tr>

							</tbody>
						</table>
					</div>
				</div>
				<div class="row mt-3 mx-1">

					<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-left:10%;margin-bottom:130px;">
						<input data-loading-text="Adding Customer/s ..." type="submit" name="customer_btn" value="Save Customer/s" class="btn btn-success submit_btn" style="height:50px;width:150px;">
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
		$("#customer-form").submit(function(e) {
			e.preventDefault();
			$.ajax({
				url: 'ajaxCall/updateCustomer.php',
				method: 'POST',
				data: $('#customer-form').serialize(),
				success: (data) => {
					if (data == 1) {
						alert('Customer details Updated.');
						location.replace('customer_list.php');
					} else {
						alert(data);
					}

				}
			});
		});
	})
</script>