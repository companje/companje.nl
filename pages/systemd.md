# folder
```BASH
sudo systemctl --failed # welke services zijn niet goed gestart

sudo systemctl daemon-reload  # als de code veranderd is, hierna nog wel een restart doen

sudo journalctl -u charge_check.service -b   # logs

sudo journalctl  -u mfxml2json.service -f

sudo systemctl daemon-reload && sudo systemctl restart mfxml2json
```
