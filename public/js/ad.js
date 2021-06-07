$(document).ready(function () {
  let random = Math.floor(Math.random() * 5) + 1;
  if (random === 1) {
    $.ajax({
      url: "/reklama/",
      dataType: "JSON",
    }).done(function (data) {
      const ad = $(".ad");
      ad.removeClass("hidden");
      ad.find(".close").on("click", function () {
        ad.addClass("hidden");
      });
      ad.find(".image").attr("src", "/img/ad/" + data.image + ".jpg");
    });
  }
});
