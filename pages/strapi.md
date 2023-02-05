# create strapi project
```bash
# make sure to have an LTS version of Node: 
#  * Visit https://nvm.sh and use the curl command to install it.
#  * nvm install --lts

# make sure not to have spaces in your folder structure because better-sqlite3 fails installing on this.

npx create-strapi-app@latest my-project --quickstart
  
# run develop
to be able to create new content types start strapi in develop mode
```bash
npm run develop
# http://localhost:1337/admin
# http://localhost:1337
```

# install import/export plugin
https://market.strapi.io/plugins/strapi-plugin-import-export-entries

