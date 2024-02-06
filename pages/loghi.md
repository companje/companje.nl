## Install Loghi on Ubuntu
* install git
* install docker
  * https://docs.docker.com/engine/install/ubuntu/#install-using-the-repository
* see [[cuda]]
* vergeet ook niet de [nvidia docker container-toolkit](https://docs.nvidia.com/datacenter/cloud-native/container-toolkit/latest/install-guide.html#configuration) (Eerst 1:install with APT, dan 2:Configuration)
* (om te controleren of je container-toolkit goed werkt: ```sudo docker run --gpus all nvcr.io/nvidia/k8s/cuda-sample:nbody nbody -gpu -benchmark```)
