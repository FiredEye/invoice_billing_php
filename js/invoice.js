$(document).ready(function () {
  $(document).on("click", "#checkAll", function () {
    $(".itemRow").prop("checked", this.checked);
  });
  $(document).on("click", ".itemRow", function () {
    if ($(".itemRow:checked").length == $(".itemRow").length) {
      $("#checkAll").prop("checked", true);
    } else {
      $("#checkAll").prop("checked", false);
    }
  });

  $(document).on("keyup", "[id^=quantity_]", function () {
    calculateTotal();
  });
  setInterval(() => {
    calculateTotal();
  }, 500);

  $(document).on("keyup", "#taxRate", function () {
    calculateTotal();
  });
  $(document).on("keyup", "#amountPaid", function () {
    var amountPaid = $(this).val();
    var totalAftertax = $("#totalAftertax").val();
    if (amountPaid && totalAftertax) {
      totalAftertax = totalAftertax - amountPaid;
      $("#amountDue").val(totalAftertax);
    } else {
      $("#amountDue").val(totalAftertax);
    }
  });
});
function calculateTotal() {
  var totalAmount = 0;
  $("[id^='price_']").each(function () {
    var id = $(this).attr("id");
    id = id.replace("price_", "");
    var price = $("#price_" + id).val();
    var quantity = $("#quantity_" + id).val();
    if (!quantity) {
      quantity = 0;
    }
    var total = price * quantity;
    $("#total_" + id).val(parseInt(total));
    totalAmount += total;
  });
  $("#subTotal").val(parseInt(totalAmount));
  var taxRate = $("#taxRate").val();
  var subTotal = $("#subTotal").val();
  if (subTotal) {
    var taxAmount = (subTotal * taxRate) / 100;
    $("#taxAmount").val(taxAmount);
    subTotal = parseFloat(subTotal) + parseFloat(taxAmount);
    $("#totalAftertax").val(Math.round(subTotal));
    var amountPaid = $("#amountPaid").val();
    var totalAftertax = $("#totalAftertax").val();
    if (amountPaid && totalAftertax) {
      totalAftertax = totalAftertax - amountPaid;
      $("#amountDue").val(totalAftertax);
    } else {
      $("#amountDue").val(Math.round(subTotal));
    }
  }
}
