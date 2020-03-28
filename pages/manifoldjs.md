---
title: ManifoldJS
---

* http://www.html5hacks.com/blog/2015/08/06/manifoldjs-quick-start/
* http://blog.npmjs.org/post/131244760570/guest-post-build-for-devices

```sudo npm install -g manifoldjs```

```
WARNING: No manifest found. A new manifest will be created.
WARNING: Manifest validation  (Android) - Launcher icons of the following sizes are required: 48x48, 72x72, 96x96, 144x144, 192x192, 512x512[icons]
WARNING: Manifest validation  (iOS) - An app icon of the following sizes is required: 76x76, 120x120, 152x152 and 180x180[icons]
WARNING: Manifest validation  (iOS) - An 1024x1024 app icon for the App Store is required[icons]
WARNING: Manifest validation  (iOS) - A launch image of the following sizes is required: 750x1334, 1334x750, 1242x2208, 2208x1242, 640x1136, 640x960, 1536x2048, 2048x1536, 768x1024 and 1024x768[icons]
SUGGESTION: Manifest validation  (Chrome) - A 48x48 icon should be provided for the extensions management page (chrome://extensions)[icons]
SUGGESTION: Manifest validation  (Chrome) - It is recommended to have a 16x16 favicon[icons]
WARNING: Manifest validation  (Chrome) - A 128x128 icon is required for the installation process and the Chrome Web Store[icons]
SUGGESTION: Manifest validation  (Firefox) - Firefox 2.0 onwards, an 512x512 icon is recommended for Firefox Marketplace and devices[icons]
WARNING: Manifest validation  (Firefox) - A 128x128 icon is required for the Firefox Marketplace and the devices[icons]
SUGGESTION: Manifest validation  (Firefox) - It is recommended to provide icon sizes for multiple platforms, including: 16x16, 32x32, 48x48, 64x64, 90x90, 128x128 and 256x256[icons]
SUGGESTION: Manifest validation  (All Platforms) - It is recommended to specify a set of access rules that represent the navigation scope of the application[mjs_access_whitelist]
ERROR: Failed to create the base application. The Cordova project could not be created successfully.
One or more errors occurred when generating the application. For more information, run manifoldjs with the diagnostics level set to debug (e.g. manifoldjs [...] -l debug)
```

# ERROR: Failed to retrieve manifest from site.
This error is generated when HTTP response is not 200 OK. For example in case of 401 or 403 (Authentication).
https://github.com/manifoldjs/ManifoldJS/blob/510145f8c8c623d869174354cf2abe514d536395/lib/manifestTools.js#L67
