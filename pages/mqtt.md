---
title: MQTT 
---
"a publish-subscribe-based messaging protocol"


* <https://test.mosquitto.org/>
* <https://simplifiedthinking.co.uk/2015/10/03/install-mqtt-server/>

```bash
brew install mosquitto
ln -sfv /usr/local/opt/mosquitto/*.plist ~/Library/LaunchAgents
launchctl load ~/Library/LaunchAgents/homebrew.mxcl.mosquitto.plist

mosquitto_sub -t topic/state

mosquitto_pub -t topic/state -m "Hello World"

```