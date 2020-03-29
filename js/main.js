window.onkeydown = function(event) {
  if (event.keyCode==69 && event.metaKey) { //cmd+E
    var page = window.location.pathname;
    page = page.replace(new RegExp("/", "g"), "");
    page = page.replace(new RegExp(".html", "g"), "");
    window.open("https://github.com/companje/companje.github.io/edit/master/pages/" + page + ".md");
  }

  if (event.keyCode==191) { //  '/'
    $('#txtSearch').focus();
    event.preventDefault();
  }

};

$("#frmSearch").submit(function( event ) {
  location.href = "/" + $('#txtSearch').val();
  event.preventDefault();
});
