## System update

```bash
sudo apt-get update
sudo apt-get upgrade
sudo apt-get autoremove
```

## LightTPD

```bash
sudo apt-get install lighttpd
```

Edit the `/etc/lighttpd/lighttpd.conf` configuration file:

```bash
server.document-root    = "/home/ludo/git/sigfox-ep-dl-interface/"
server.port             = <lighttpd_port>
```

```bash
sudo apt-get install php-cgi

sudo lighty-enable-mod fastcgi
sudo lighty-enable-mod fastcgi-php

sudo service lighttpd force-reload
```
