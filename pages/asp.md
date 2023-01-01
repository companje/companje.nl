ASP / ASP classic

# install IIS with ASP classic on Windows Server 12
```batch
Start /w pkgmgr /iu:IIS-WebServerRole;IIS-WebServer;IIS-CommonHttpFeatures;IIS-StaticContent;IIS-DefaultDocument;IIS-DirectoryBrowsing;IIS-HttpErrors;IIS-ApplicationDevelopment;IIS-ASP;IIS-ISAPIExtensions;IIS-HealthAndDiagnostics;IIS-HttpLogging;IIS-LoggingLibraries;IIS-RequestMonitor;IIS-Security;IIS-RequestFiltering;IIS-HttpCompressionStatic;IIS-WebServerManagementTools;IIS-ManagementConsole;WAS-WindowsActivationService;WAS-ProcessModel;WAS-NetFxEnvironment;WAS-ConfigurationAPI
```

# add site
```batch
set path=%path%;%systemroot%\system32\inetsrv\
appcmd add site /name:xyz2020 /id:2 /physicalPath:c:\xyz2020 /bindings:http/*:80:xyz.youdomain.nl 
```

# ASP Error: ADODB.Connection error '800a0e7a' Provider cannot be found. It may not be properly installed. Also error code 126 in ODBC connection.
Solution "Enable 32-bit applications" needs to be enabled in the connection pool for the website, within IIS Manager 7.
```text
IIS / Application Pools / YourPool / Advanced Settings
Advanced / Enable 32-Bit Applications: True
```





