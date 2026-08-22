## Installation

### System update

```bash
sudo apt-get update
sudo apt-get upgrade
sudo apt-get autoremove
```

### Interface

```bash
mkdir git
cd git
git clone https://github.com/Ludovic-Lesur/sigfox-ep-dl-interface.git
```

### LightTPD

```bash
sudo apt-get install lighttpd
```

Edit the `/etc/lighttpd/lighttpd.conf` configuration file:

```bash
server.document-root    = "<sigfox-ep-dl-interface path>"
server.port             = <lighttpd_port>
```

```bash
sudo apt-get install php-cgi

sudo lighty-enable-mod fastcgi
sudo lighty-enable-mod fastcgi-php

sudo service lighttpd force-reload

sudo git config --global --add safe.directory '*'
sudo git config --system --add safe.directory '*'
```

### Shared file permissions

Launch the interface once to create the `sigfox_ep_dl_messages.json` file.

```bash
cd git
sudo chown <user>:www-data sigfox-ep-dl-interface/
sudo chmod g+w sigfox-ep-dl-interface/
sudo usermod -aG www-data <user>
cd sigfox-ep-dl-interface
sudo chmod g+w sigfox_ep_dl_messages.json
newgrp www-data
```

## Local testing

```bash
cd git/sigfox-ep-dl-interface
php -S localhost:8000
```
