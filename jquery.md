---
title: =========jQuery=========
---

#  jChartFX 
http://www.jchartfx.com/

#  jQuery alternatives 
* http://www.sitepoint.com/top-5-jquery-ui-alternatives/

#  POST 
```jquery
$.post( "ajax/test.html", function( data ) {
  $( ".result" ).html( data );
});
```

#  GET 
```jquery
$.get("ajax/test.html", function(data) {
  $(".result").html(data);
  alert("Load was performed.");
});
```

# Getting over jQuery
http://blog.ponyfoo.com/2013/07/09/getting-over-jquery

# Hello World
```jquery
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<script>
$(document).ready(function() {
  alert("Hello World");
})
```

# parsing html table (not finished)
```jquery
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<script>
$(document).ready(function() {
  
  $('#result').load('index.php', function(data) {
     
    var rows = $("td[bgcolor=#00ffff]").parent();
    $(rows).each(function(index) {
      var cols = $(this).children("th,td");
      $(cols).each(function(index) {
      console.log($(this).html());//.replace(/n/g, '&nbsp;'));
      console.log($(this));
      });
      console.log("==========================");
    });
  });

});
</script>

<div id="result">Loading...</div>
```

# headless browser
* http://stackoverflow.com/questions/5940557/how-can-i-use-jquery-on-server-side-javascript
* http://phantomjs.org/
* http://zombie.labnotes.org/

# this & children
```jquery
//$(e.currentTarget)  ===== $(this)
$(this).children(".description").text()
```

* http://webdesignerwall.com/tutorials/jquery-tutorials-for-designers
* http://view.jqueryui.com/grid/grid-editing/todo-app.html

```jquery
<script id="todo-list-item" type="text/x-jquery-tmpl">
  <li (if done)class="done"(/if)>
    <label>
      <input type="checkbox" id="todo-${id}" (if done)checked="checked"(/if) />
      ${title}
    </label>
  </li>
</script>
```

# hover menuitems in wordpress
```jquery
jQuery(document).ready(function() {

  jQuery(".menu-item img").hover(
    function () {
      if (jQuery(this).parents().hasClass("current_page_item")) return;
      jQuery(this).attr("src",jQuery(this).attr("src").replace(".png", "_over.png"));
    },
    function () {
      if (jQuery(this).parents().hasClass("current_page_item")) return;
      jQuery(this).attr("src",jQuery(this).attr("src").replace("_over.png", ".png"));
    }
  );

  var img = jQuery('.current_page_item img');
  img.attr("src",img.attr("src").replace(".png", "_active.png"));

});
```
