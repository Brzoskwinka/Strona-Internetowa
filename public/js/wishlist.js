$(document).ready(function () {
  $.ajax("/lista/liczba").done(function (data) {
    if (data.count > 0) {
      $(".topbar-wishlist-count").removeClass("hidden");
      $(".topbar-wishlist-count").text(data.count);
    }
  });
});
