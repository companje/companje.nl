# show single image from querystring in self opening fancybox
```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css"
    />
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
    <script>
    if (location.search.indexOf("?")==0) {
      const fancybox = new Fancybox([{
            src: location.search.substring(1), //skip questionmark from querystring
            type: "image"
      }]);
    }
    </script>
  </body>
</html>
```
