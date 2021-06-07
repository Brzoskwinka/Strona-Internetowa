$(".product-images-image").on("click", function () {
  const src = $(this).attr("src");
  $(".product-image").attr("src", src);
});
$(".product-review-score-choose").on("click", function () {
  $(".product-review-score-choose").each(function () {
    $(this).removeClass("active");
  });
  $(this).addClass("active");
  $(".product-review-score-final").val($(this).data("score"));
});
$(".product-fullscreen-close").on("click", function () {
  $(".product-fullscreen-overlay").addClass("hidden");
});
$(".product-image").on("click", function () {
  $(".product-fullscreen-image").attr("src", $(this).attr("src"));
  $(".product-fullscreen-overlay").removeClass("hidden");
});
