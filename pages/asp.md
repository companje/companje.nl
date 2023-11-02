ASP / ASP classic

# output result of database query as JSON
```vbscript
<!--#include virtual="/scripts/aspJSON1.19.asp" -->
<pre>
<%
db_path = Request.ServerVariables("APPL_PHYSICAL_PATH") + "DB.mdb"
set conn = Server.CreateObject("ADODB.Connection")
set rs = Server.CreateObject("ADODB.Recordset")
conn.mode = adModeRead
conn.open "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & db_path & ";"
rs.open "select Iduds, Zplaat, trefwoord from TABLE where Zplaat<>'' and Zplaat<>'.'", conn

itemIndex = 0
set json = new aspJSON
with json.data
  do until rs.EOF
    .Add itemIndex, json.Collection()
    with .item(itemIndex)
      for each x in rs.fields
        if x.value<>"" then .Add x.name, x.value
      next
    end With
    itemIndex = itemIndex + 1
    rs.moveNext
  loop
end with

response.write json.JSONoutput()         
%>
```

# set 404 page in web.config
```xml
<configuration>
    <system.webServer>
        <httpErrors errorMode="Custom" defaultResponseMode="File" >
         <!-- als je Detailed informatie wilt bijv wat de fout precies is dan moet je hierboven 'Detailed' invullen0-->
         <remove statusCode="404" />
         <error statusCode="404" path="404.html" />
       </httpErrors>
        <defaultDocument>
...
```

# install IIS with ASP classic on Windows Server 12
(also works fine on Windows 11)
```batch
Start /w pkgmgr /iu:IIS-WebServerRole;IIS-WebServer;IIS-CommonHttpFeatures;IIS-StaticContent;IIS-DefaultDocument;IIS-DirectoryBrowsing;IIS-HttpErrors;IIS-ApplicationDevelopment;IIS-ASP;IIS-ISAPIExtensions;IIS-HealthAndDiagnostics;IIS-HttpLogging;IIS-LoggingLibraries;IIS-RequestMonitor;IIS-Security;IIS-RequestFiltering;IIS-HttpCompressionStatic;IIS-WebServerManagementTools;IIS-ManagementConsole;WAS-WindowsActivationService;WAS-ProcessModel;WAS-NetFxEnvironment;WAS-ConfigurationAPI
```

# add site
```batch
set path=%path%;%systemroot%\system32\inetsrv\
appcmd add site /name:xyz2020 /id:2 /physicalPath:c:\xyz2020 /bindings:http/*:80:xyz.youdomain.nl 
```

# show errors
```text
IIS > your site > ASP > Debugging Properties > Send Errors To Browser: True
```

# HTTP Error 401.3 - Unauthorized
solution: Edit permissions of site in IIS Manager. Add *IUSR* with the default rights (read&execute, list folder contents, read)
(you might need to add IIS_IUSRS too?).

# ASP Error: ADODB.Connection error '800a0e7a' Provider cannot be found. It may not be properly installed. Also error code 126 in ODBC connection.
Solution "Enable 32-bit applications" needs to be enabled in the connection pool for the website, within IIS Manager 7.
```text
IIS / Application Pools / YourPool / Advanced Settings
Advanced / Enable 32-Bit Applications: True
```
# read rows and fields from Access database
```vbscript
<pre>
<%
db_path = Server.mapPath("db.mdb")
set conn = Server.CreateObject("ADODB.Connection")
conn.mode = adModeRead
conn.open "Provider=Microsoft.Jet.OLEDB.4.0;Data Source=" & db_path & ";"

set rs = Server.CreateObject("ADODB.Recordset")
rs.open "select * from tabelObject", conn

do until rs.EOF
    for each x in rs.fields
        if x.value<>"" then response.write(x.name & " = " & x.value & vbCrlf)
    next
    response.write("<hr>")
    rs.moveNext
loop
%>
```
# Response Buffer Limit Exceeded
you could change the Response Buffer from 4MB to 64MB
```text
IIS > your site > ASP > Behavior > Limits Properties > Response Buffering Limit: 67108864
```



