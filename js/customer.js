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
});
