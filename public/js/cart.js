$(document).ready(function () {
  $(".cart-comp-check").on("click", function () {
    $.ajax({
      url: "/koszyk/kompatybilnosc",
      dataType: "JSON",
      method: "POST",
      data: {
        prod1: $(".cart-prod1").val(),
        prod2: $(".cart-prod2").val(),
      },
    }).done(function (data) {
      $(".cart-comp").each(function () {
        $(this).addClass("hidden");
        if ($(this).data("comp-code") === data) {
          $(this).removeClass("hidden");
          setTimeout(
            function (bar) {
              bar.addClass("hidden");
            },
            5000,
            $(this)
          );
        }
      });
    });
  });
});
