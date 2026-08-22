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
sudo chown <user>:www-data sigfox-ep-dl-interface/
sudo chmod g+w sigfox-ep-dl-interface/
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

## Local testing

```bash
cd git/sigfox-ep-dl-interface
php -S localhost:8000
```
