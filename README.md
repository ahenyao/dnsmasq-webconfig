# Configure dnsmasq from your browser!
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

![Arch](https://img.shields.io/badge/Arch%20Linux-1793D1?logo=arch-linux&logoColor=fff&style=for-the-badge)
![Debian](https://img.shields.io/badge/Debian-D70A53?style=for-the-badge&logo=debian&logoColor=white)
![Openwrt](https://img.shields.io/badge/OpenWRT-00B5E2?style=for-the-badge&logo=OpenWrt&logoColor=white)
![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)

![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)
![Nginx](https://img.shields.io/badge/nginx-%23009639.svg?style=for-the-badge&logo=nginx&logoColor=white)

## About
Dnsmasq WebConf is a simple web-based tool that makes it
easy to configure your Dnsmasq server without manually editing config files.
With simple and intuitive interface, you can easily create and manage:
* DNS records (A, AAAA, CNAME, MX, TXT, SRV)
* DHCP settings
* PXE boot configurations

## Features
- Easy and intuitive (also mobile-friendly) web interface
- Dark theme for your eyes
- 

## Requirements

* A web server (e.g., Apache, Nginx)
* PHP
* Dnsmasq

## Installation

> [!CAUTION]
> This tool should never be exposed to the public internet.
> The best practice is to restrict access to localhost or
> other trusted IPs only, to minimize security risks.

> [!NOTE]
> In this guide we assume that websites are stored under `/srv/http` directory
> (e.g. `/srv/http/site1`, `/srv/http/site2` etc.)


1. Go to your web server root directory
```bash
$ cd /srv/http
```


2. Clone this repository to your server
```bash
$ git clone https://github.com/ahenyao/dnsmasq-webconf.git
```


3. Create a configuration directory
```bash
$ sudo mkdir /etc/dnsmasq.webconf
```


4. Set correct ownership and permissions

  > [!IMPORTANT]
  > This section is for Apache Web Server. For those using Nginx, there's a separate section with instructions below.

   a. Find the user and group under which your web server runs

   For Arch Linux

   ```bash
   $ grep -iE "^User|^Group" /etc/httpd/conf/httpd.conf
   ```
   For Debian/Ubuntu

   ```bash
   $ grep -iE "^User|^Group" /etc/apache2/apache2.conf
   ```
  > [!IMPORTANT]
  > This section is for Nginx Web Server

   a. Find the user and group under which your web server runs

   ```bash
   $ sudo ps aux | grep nginx
   ```
   ```
   root      312948  0.0  0.0  14836  2772 ?        Ss   20:30   0:00 nginx: master process /usr/bin/nginx
   http      312949  0.0  0.0  15284  5036 ?        S    20:30   0:00 nginx: worker process
   nya       314021  0.0  0.0   6472  3904 pts/1    S+   20:31   0:00 grep --color=auto nginx
   ```
   Look for the line with `nginx: worker process`. In this case as both user and group we will use `http`.

   b. Change ownership of config directory
   
   > [!IMPORTANT]
   > Replace `user:group` with the actual user and group found in the previous step

   ```
   $ sudo chown user:group /etc/dnsmasq.webconf
   $ sudo chmod 755 /etc/dnsmasq.webconf
   ```


5. Configure Dnsmasq

All configurations for this tool are stored in the `/etc/dnsmasq.webconf` directory, so we need to tell Dnsmasq to look in this location
```bash
$ sudo nano /etc/dnsmasq.conf
```
> [!WARNING]
> If you already have configurations in `dnsmasq.conf` run this first to back it up
```bash
$ sudo mv /etc/dnsmasq.conf /etc/dnsmasq.webconf/extra.conf
```

Put only this inside the file
```
conf-dir=/etc/dnsmasq.webconf/,*.conf
```
This will include all `*.conf` files from `/etc/dnsmasq.webconf`


6. Configure web server

   > [!IMPORTANT]
   > Replace example.com with your local domain.

Apache example config

For Arch Linux
```bash
$ sudo nano /etc/httpd/conf/extra/httpd-vhosts.conf
```

For Debian/Ubuntu
```bash
$ sudo nano /etc/apache2/sites-available/dnsmasq-webconf.conf
$ sudo a2ensite dnsmasq-webconf.conf
```

Add this at the end of file
```
<VirtualHost *:80>
  ServerName dnsmasq.example.com
  DocumentRoot /srv/http/dnsmasq-webconf
  <Directory /srv/http/dnsmasq-webconf>
    Require local
  </Directory>
  ErrorLog ${APACHE_LOG_DIR}/dnsmasqWebConf_error.log
  CustomLog ${APACHE_LOG_DIR}/dnsmasqWebConf_access.log combined
</VirtualHost>
```

Nginx example config
```
server {
    listen 80;
    server_name dnsmasq.example.com;
    root /srv/http/dnsmasq-webconf;
    index index.php;
    
    location / {
        allow 127.0.0.0/8;
        deny all;
    }

    location ~* \.php$ {
        fastcgi_index   index.php;
        fastcgi_pass    127.0.0.1:9000;
        include         fastcgi_params;
        fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;
        fastcgi_param   SCRIPT_NAME        $fastcgi_script_name;
    }
    
    error_log ${NGINX_LOG_DIR}/dnsmasqWebConf_error.log;
    access_log ${NGINX_LOG_DIR}/dnsmasqWebConf_access.log;
}
```

7. Enabling and restarting services
```bash
$ sudo systemctl enable apache2    # or httpd on Arch
$ sudo systemctl enable nginx      # if you're using Nginx
$ sudo systemctl enable dnsmasq

$ sudo systemctl restart apache2    # or httpd on Arch
$ sudo systemctl restart nginx      # if you're using Nginx
$ sudo systemctl restart dnsmasq
```

8. Setting up `/etc/hosts` file

> [!IMPORTANT]
> If you changed domain name at step 6, change it here too

It is recommended to let system know that when we visit `dnsmasq.example.com` it should show us dnsmasq-webconfig. This is done with `hosts` file.
```bash
$ sudo nano /etc/hosts
```
Add this at the end
```
127.0.0.1 dnsmasq.example.com
```

## Opening Issues
If you find a bug or want to request some feature, feel free to open an issue
1. Check if it wasn't mentioned
2. If not, click on the "New issue" button
3. Describe issue or feature. Don't forget to add OS and dependencies versions or screenshots.

I will try to reach out as fast as I can