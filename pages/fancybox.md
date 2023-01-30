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
    //strip ? from querystring
    const url = location.search.indexOf("?")!=0 ? "" : location.search.substring(1)

    //open image in querystring in fancybox
    const fancybox = new Fancybox([
        {
            src: url,
            type: "image",
        },
    ]);
    </script>
  </body>
</html>
```
