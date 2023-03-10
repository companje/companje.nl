# folder
```BASH
cd /etc/systemd/system
```

# which services didn't startup
```
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
```
sudo journalctl  -u mfxml2json.service -f
```
