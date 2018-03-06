---
title: GEO
---

# IP to country API
* <http://ip-api.com/json>
* <http://freegeoip.net/json/>

# async geocode to CSV
```html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

	<script type="text/javascript">
	$(function(){

		var csv = [];
		csv.push(["city","country_code","country_name","ip","latitude","longitude","metro_code","region_code","region_name","time_zone","zip_code"]);

		var i=0;
		next(i);

		function next() {
			$.getJSON("http://freegeoip.net/json/" + ips[i], function(data) {
				console.log(data);
        var row = data;

				var cols = [];
				cols.push(row.city || "");
				cols.push(row.country_code || "");
        cols.push(row.country_name || "");
				cols.push(row.ip || "");
				cols.push(row.latitude || "");
				cols.push(row.longitude || "");
				cols.push(row.metro_code);
				cols.push(row.region_code || "");
        cols.push(row.region_name || "");
				cols.push(row.time_zone || "");
				cols.push(row.zip_code || "");

				var r=JSON.stringify(cols).substr(1);
				csv.push(r.substr(0,r.length-1));

				if (i<ips.length-1) next(++i); else done();
			});
		}


    function done() {
      var text = csv.join("\n");

      //download
      var element = document.createElement('a');
      element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
      element.setAttribute('download', "geo.csv");
      element.style.display = 'none';
      document.body.appendChild(element);
      element.click();
      document.body.removeChild(element);
    }
	});

	function initMap() {
		console.log("test");
	}
	</script>
</head>
<body>
	<div id="map" style="height: 500px;"></div>
</body>
</html>

<script>
	var ips = ["8.8.8.8", "1.1.1.1"]; //etc
</script>
```

