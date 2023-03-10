# folder
```bash
cd /etc/systemd/system
```

# create a your-service.service file
```editorconfig
[Unit]
Description=YOUR_SERVICE description
After=network.target

[Service]
User=YOUR_USER_NAME  # python modules were not found when username not supplied in my case.
WorkingDirectory=SCRIPT_FOLDER
ExecStart=/usr/bin/python3 YOUR_PYTHON_SCRIPT.py
Restart=always

[Install]
WantedBy=multi-user.target
```

# which services didn't startup
```bash
sudo systemctl --failed
```

# when your code has changed
```bash
sudo systemctl daemon-reload 
sudo systemctl restart mfxml2json   # name of your service
```

# logs
```bash
sudo journalctl -u charge_check.service -b
```

# log tail -f
```bash
sudo journalctl  -u mfxml2json.service -f
```
