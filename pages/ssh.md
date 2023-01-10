---
title: SSH
---

# port in ssh config
```
Host dev
    HostName example.com
    User fred
    Port 2322
```
   
# locale warnings through SSH on MacOS / iTerm
```
WARNING! Your environment specifies an invalid locale.
 The unknown environment variables are:
   LC_CTYPE=UTF-8 LC_ALL=

locale: Cannot set LC_CTYPE to default locale: No such file or directory
locale: Cannot set LC_ALL to default locale: No such file or directory
: warning: Setting locale failed.
: warning: Please check that your locale settings:
	LANGUAGE = (unset),
	LC_ALL = (unset),
	LC_TERMINAL = "iTerm2",
	LC_CTYPE = "UTF-8",
	LANG = "C.UTF-8"
    are supported and installed on your system.
: warning: Falling back to a fallback locale ("C.UTF-8").
```
Solution: Go to iterm2 Preferences -> Profiles -> Terminal and disable option Set locale variables automatically.
* https://docs.spryker.com/docs/scos/dev/troubleshooting/troubleshooting-spryker-in-vagrant-issues/macos-issues/mac-osx-iterm2-locale-error.html#description


# ssh-agent / ssh-add
set password bij ssh-keygen is erg belangrijk. Om te voorkomen dat de private key op je eigen computer in verkeerde handen komt. maar moet je dan nog steeds telkens een passphrase invoeren? Ja, maar daar is een oplossing voor: ssh-agent (gebruik ssh-add  ~/.ssh/demo.ed25519  om key toevoegen aan ssh-agent). die bewaart in een lopend process en stelt het beschikbaar via een socket aan ssh.

# remote autocomplete
na opname in authorized_keys (en ssh-agent?) krijg je remote autocomplete. je kunt bijv bij scp op tab drukken voor completion.

# create key (elliptic curve)
```bash
ssh-keygen -t ed25519 -a 420 -f ~/.ssh/demo.ed25519 -C "my key for today"
```

# hostname aliases
```config
Host smtp imap www
  Hostname %h.example.com
  User myusername
  PreferredAuthentications publickey
  IdentityFile ~/.ssh/demo.ed25519
```

# SFTP
* https://wiki.filezilla-project.org/Command-line_arguments_(Client)
```bash
sftp root@192.168.5.1:22
```
error: /usr/libexec/sftp-server: not found

## SFTP - FATAL ERROR: Connection reset by peer Error: Could not connect to server sftp
check logfile:
```
tail -f /var/log/auth.log
```

# create SSH tunnel
```bash
ssh -NL 8157:localhost:8888 paperspace@74.82.31.122
```

# update key in known_hosts
```bash
ssh-keygen -R SERVER_IPADDRESS
```

# ignore StrictHostKeyChecking for once 
```bash
ssh -o StrictHostKeyChecking=no root@10.0.0.195
```

# sshfs
SSHFS lijkt ook redelijk bruikbaar zonder gui te gebruiken. Mogelijk zelfs wel stabieler: http://doodle3d.com/help/remote-disk-mount

# sshfs with debug info
```bash
mkdir local-folder
sshfs -odebug,sshfs_debug,loglevel=debug user@server:/ local-folder
...
umount local-folder
```

# add self to authorized_keys oneliner
```bash
cat ~/.ssh/id_rsa.pub | ssh SERVER 'cat >> ~/.ssh/authorized_keys'

#openwrt / wifibox
cat ~/.ssh/id_rsa.pub | ssh wifibox 'cat >> /etc/dropbear/authorized_keys'
```

# ssh agent forwarding
De volgende tutorial omschrijft duidelijk hoe je ssh agent forwarding kan instellen. Hiermee kan je (via ssh) op een ander apparaat een git pull doen met jou eigen ssh key. 
https://developer.github.com/guides/using-ssh-agent-forwarding/

Het vereist wel dat de remote een ssh url is. Dit kan je bijv. doen door het te klonen vanaf de SSH clone URL (git@github.com:...)

# openssh in cygwin
https://www.youtube.com/watch?v=CwYSvvGaiWU

# ssh-keygen more secure key
```bash
  ssh-keygen -t rsa -b 4096 -C "name@domain.com"
```

# connection to .... closed
check if user belongs to 'ssh' group (and decide if you want this :-)

# disable host strict checking
in ''~/.ssh/config''
```bash
Host wifibox
  User root
  Hostname 192.168.5.1
  StrictHostKeyChecking no
  UserKnownHostsFile=/dev/null
```

# aliases
In ~/.ssh/config kun je aliassen aanmaken zodat je geen gebruikersnaam/obscure ip's etc meer in hoeft te typen. B.v. voor zowel gebruiker root als ortec (met bash-completion kun je zelfs tab gebruiken om het voor je in te vullen). Zie Wouter's mail van 1 mei '13

# kill an ssh connection
```
Enter ~.
```

# ssh tunnel
```bash
ssh -L 8080:localhost:80 192.168.0.1
```

# restart ssh daemon
```bash
/etc/init.d/sshd restart
```

You need to do this after manually adding a user to the sshd_config file ([[http://fixunix.com/ssh/74233-ssh-login-error-permission-denied-please-try-again.html|see this thread]])


# Permission denied (publickey)
You might be cloning a non-anonymous repository from github. If you just started using github (on your machine) github first needs to trust you. You can add the contents of ~/.ssh/id_rsa.pub to the SSH public key list on github at your account settings. If you don't want that just clone the repository through https or readonly.

Some very usefull info about ssh keys etc: http://help.github.com/ssh-issues/

# Create id_rsa files with ssh-keygen
just run ''ssh-keygen''

# Adding your public key to the serveremember your git user's password
```bash
scp ~/.ssh/id_rsa.pub user@remote.example.com:/tmp/id_rsa.pub
mkdir ~/.ssh
chmod 700 ~/.ssh
cat /tmp/id_rsa.pub >> ~/.ssh/authorized_keys
```

hmm.. het lijkt dat je de id_dsa.pub moet toevoegen

op de client:
```bash
ssh-keygen -t dsa -f $HOME/.ssh/id_dsa -P ''
scp ~/.ssh/id_dsa.pub SERVER:/tmp/id_dsa.pub
```

op de server:
```bash
cat /tmp/id_dsa.pub >> ~/.ssh/authorized_keys
```
