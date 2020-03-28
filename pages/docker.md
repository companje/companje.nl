---
title: Docker
---

# Docker Compose
"Compose is a tool for defining and running multi-container Docker applications."
Configure containers using YAML
https://docs.docker.com/compose/

#  on OSX 
https://docs.docker.com/docker-for-mac/

#  install docker on ElementaryOS (freya) 
* https://github.com/docker/docker/issues/12863#issuecomment-110035351

#  error: Host "dev" does not exist
docker is configured to use the default machine with IP 192.168.99.100
For help getting started, check out the docs at https://docs.docker.com
  Host "dev" does not exist
  
solution: Run this command to configure your shell:
  eval "$(docker-machine env dev)"

#  ps 
  docker ps -a
  docker ps -a -s


* http://doodle3d.com/help/docker
* http://www.doodle3d.com/help/build-environment-docker

# uninstall docker on osx (for later reinstall)
* http://therealmarv.com/blog/how-to-fully-uninstall-the-offical-docker-os-x-installation/

# kitematic
* https://kitematic.com/
  
vanuit kitematic de terminal openen opent bash met extra ENV variables.
  bash -c "clear && DOCKER_HOST=tcp://192.168.99.100:2376 DOCKER_CERT_PATH=/Users/rick/.docker/machine/machines/dev DOCKER_TLS_VERIFY=1 $SHELL"
je kunt ook vanuit de 'gewone' terminal deze variabelen setten met export:
  export DOCKER_HOST=tcp://192.168.99.100:2376 DOCKER_CERT_PATH=/Users/rick/.docker/machine/machines/dev DOCKER_TLS_VERIFY=1
  
# tutorial steps=
```bash
docker version
docker search tutorial
docker pull learn/tutorial #learn is the username, tutorial the image name
docker run learn/tutorial echo "hello world"
docker run learn/tutorial apt-get install -y ping
docker ps -l
docker commit 698 learn/ping #698 are the first letters of the ID of the container. This command returns the *image ID*
docker run learn/ping ping www.google.com
docker ps
docker inspect efe #efe are the first letters of the ID (container ID or image ID?)
docker images #shows a list of the images on your system
docker push learn/ping
# next steps:
#   sign up for a docker hub account: https://hub.docker.com/account/signup/
#   install docker engine: https://docs.docker.com/installation/ (https://docs.docker.com/installation/mac/)
```

# 'run'=
start een nieuwe container

# 'start' opent een bestaande
   docker start -i prickly_poincare     # -i means interactive
   
# start boot2docker
  boot2docker start
  
# pull & run debian
```bash
docker pull debian
docker run -i -t debian /bin/bash
```

  
  
