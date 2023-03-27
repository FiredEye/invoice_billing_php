<?php
session_start();
include('../inc/header.php');
include '../queries/Customer.php';
$customer = new Customer();

include '../queries/Invoice.php';
$invoice = new Invoice();
$invoice->checkLoggedIn();
?>
<title>Invoice System</title>
<script src="../js/invoice.js"></script>
<style>
  th,
  td {
    text-align: center;
  }
</style>
<?php include('../inc/container.php'); ?>
<?php include('menu.php'); ?>


<div class="container-fluid" id="invoice_list">

  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">
        Invoice-List Table
      </h6>
      <div class="bg-success px-2 ms-3" id="email_msg" style="color:white; border-radius:8px;float:left;font-size:18px;">
        <?php
        if (isset($_SESSION['email_msg'])) {
          echo $_SESSION['email_msg'];
        }
        ?>
      </div>
    </div>
    <div class="card-body text-black">
      <div class="table-responsive">
        <!-- <div class="row mb-2">
          <div class="col-md-8">Search: <input type="text"></div>
        </div> -->
        <table class="table table-bordered table-sm text-black" id="myTable" width="100%" cellspacing="0">
          <thead class="thead-dark">
            <tr style="font-size:13px ;">
              <th>Invoice No.</th>
              <th>Customer Name</th>
              <th>Invoice Total</th>
              <th>Total Due</th>
              <th>Create Date</th>
              <th>Due Date</th>
              <th>E-Mail</th>
              <th>Print</th>
              <th>Edit</th>
              <th>Delete</th>

            </tr>
          </thead>
          <tbody style="font-size:15px;">
            <?php

            $invoiceList = $invoice->getInvoiceList();
            foreach ($invoiceList as $invoiceDetails) {
              $invoiceDate = date("d/M/Y", strtotime($invoiceDetails["order_date"]));
              $dueDate = date("d/M/Y", strtotime($invoiceDetails["due_date"]));
              $cust_id = $invoiceDetails["order_receiver_id"];
              $data = $customer->getCustomerbyId($cust_id);
              $row = mysqli_fetch_assoc($data);
              echo '
              <tr>
                <td>' . $invoiceDetails["order_id"] . '</td>
                <td>' . $row['first_name'] . " " . $row['last_name'] . '</td>
                <td>' . $invoiceDetails["order_total_after_tax"] . '</td>
                <td>' . $invoiceDetails["order_total_amount_due"] . '</td>
                <td>' . $invoiceDate . '</td>
                <td>' . $dueDate . '</td>
                <td><a href="utilities/email_invoice.php?invoice_id=' . $invoiceDetails['order_id'] . '" title="Mail Invoice"><i class="fa fa-envelope " style="color:#F5761A" aria-hidden="true"></i></a></td>
                <td><a href="utilities/print_invoice.php?invoice_id=' . $invoiceDetails["order_id"] . '" title="Print Invoice" target="_blank"><i class="fa fa-print text-success"></i></a></td>
                <td><a href="edit_invoice.php?update_id=' . $invoiceDetails["order_id"] . '" title="Edit Invoice" class="editInvoice"><i class="fa fa-edit text-warning"></i></a></td>
                <td><a href="#" id="' . $invoiceDetails["order_id"] . '" class="deleteInvoice' . $invoiceDetails["order_id"] . '"  title="Delete Invoice"><i class="fa fa-trash text-danger"></i></a></td>
              </tr>
            '; ?>
              <script>
                $('.deleteInvoice' + <?php echo $invoiceDetails["order_id"]; ?>).click(() => {

                  var id = $('.deleteInvoice' + <?php echo $invoiceDetails["order_id"]; ?>).attr("id");
                  if (confirm("Are you sure you want to remove Invoice No." + id + "?")) {
                    $.ajax({
                      url: "ajaxOpra/action.php",
                      method: "POST",
                      data: {
                        id: id,
                        action: "delete_invoice",
                      },
                      success: function(response) {
                        if (response == 1) {
                          alert("Invoice deleted.");
                          location.reload("invoice_list.php");
                        }
                      },
                    });
                  } else {
                    return false;
                  }
                })
              </script>
            <?php }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<?php include('../inc/footer.php'); ?>
<script>
  $(document).ready(function() {
    $('#myTable').DataTable({
      ordering: false
    });
    $('#email_msg').delay(2000).fadeOut(700);
    <?php $_SESSION['email_msg'] = ''
    ?>


  })
</script>