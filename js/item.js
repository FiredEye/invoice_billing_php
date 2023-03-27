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

  $(document).on("click", "#removeRows", function () {
    $(".itemRow:checked").each(function () {
      $(this).closest("tr").remove();
    });
    $("#checkAll").prop("checked", false);
  });
  $(document).on("click", ".deleteItem", function () {
    var id = $(this).attr("id");
    if (confirm("Are you sure you want to remove this?")) {
      $.ajax({
        url: "action2.php",
        method: "POST",
        data: { id: id, action: "delete_item" },
        success: function (response) {
          if (response != 0) {
            alert("Item Removed.");
            location.reload("item_list.php");
          }
        },
      });
    } else {
      return false;
    }
  });
});
