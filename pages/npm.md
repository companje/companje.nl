---
title: npm - node package manager
---
see [[nodejs]]

# eslint-plugin-vue ... The engine "node" is incompatible with this module. Expected version ...
I needed version 16 of node instead of 14.x
```
brew install node@16
echo 'export PATH="/usr/local/opt/node@16/bin:$PATH"' >> ~/.zshrc
# then open new shell
```

# Error: EACCES: permission denied, access '/usr/local/lib/node_modules'
```
sudo chown -R rick:admin /usr/local/lib/node_modules
```

# list all global packages without deps
```bash
$ npm list -g --depth=0
/usr/local/lib
├── add-cors-to-couchdb@0.0.6
├── airsonos@0.2.6
├── cordova@7.0.0
├── heroku@0.2.0
├── ios-deploy@1.9.1
├── json@9.0.4
├── jspm@0.16.47
├── npm@4.2.0
└── serve@1.4.0
```

# update npm itself
```
sudo npm install -g npm
```

# npm check for updates
```
npm outdated
```

# npm update packages
```
npm update
```  
# npm init creates package.json
```
npm init
npm install socket.io-client --save
```

# install express and save it to package.json
```
npm install --save express
```

# become owner of the /Users/you/.npm folder
```
sudo chown -R rick /Users/rick/.npm
```

# npm install specific version
Use this syntax to install a specific version listed there:
```
sudo npm install -g phonegap@2.9.0-rc1-0.12.2
npm install gulp-sass@1.3.3 --save
```
