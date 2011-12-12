$(document).ready(function() {
  // get rid of 'Image not available' images
  $("img[src='images/na.gif']").hide()
  // don't show broken images (sigh)
  $("img").error(function() { $(this).hide(); });

  // is this a product page? check availability at the store.
  code= $(".productName > h3 > .smallText:first").text();
  if (code) {
    $.getJSON("http://backend.rawm.us/shop/check-inventory.php?callback=?",
              { code: code },
              function(data) {
                if (data.stocked) {
                  $(".productName > h3").append("<br />Stocked at our store.")
                } else {
                  $(".productName > h3").append("<br />Available online only.")
                }
              });
  }

  // is this is a product listing page? check avail for all items.
  if ($(".productListing").length) {
    var items= [];
    var avail= "<br /><small>Stocked in-store.</small>";
    var notavail= "<br /><small>Online only.</small>";
    $(".productListing tr .productListing-data:first-child").each(function(x) {
      items.push($(this).text())
     });
    $.getJSON("http://backend.rawm.us/shop/check-inventory.php?callback=?",
              { "codes[]": items },
              function(data) {
                $.each(data, function(i,item) {
    $(".productListing tr .productListing-data:first-child").each(function(x) {
      line= $(this).text()
      if (item.code == line.substring(1,line.length - 1)) {
        $(this).siblings(':eq(3)').append(item.stocked ? avail : notavail);
      }
     });
                })
              });
  }

  // is this the gift certificate page? add note about combined/separate
  var note= '<p style="font-size: smaller; font-weight: normal">When you place your order, please let us know if you would like us to combine all of the gift certificates in your order into one single certificate for the total amount.</p>';
  $("td.hiliteText:contains('Gift Certificates')").append(note);
});
