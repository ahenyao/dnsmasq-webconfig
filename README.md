# Configure dnsmasq from your browser!
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

![Arch](https://img.shields.io/badge/Arch%20Linux-1793D1?logo=arch-linux&logoColor=fff&style=for-the-badge)
![Debian](https://img.shields.io/badge/Debian-D70A53?style=for-the-badge&logo=debian&logoColor=white)
![Openwrt](https://img.shields.io/badge/OpenWRT-00B5E2?style=for-the-badge&logo=OpenWrt&logoColor=white)
![Ubuntu](https://img.shields.io/badge/Ubuntu-E95420?style=for-the-badge&logo=ubuntu&logoColor=white)

![Apache](https://img.shields.io/badge/apache-%23D42029.svg?style=for-the-badge&logo=apache&logoColor=white)
![Nginx](https://img.shields.io/badge/nginx-%23009639.svg?style=for-the-badge&logo=nginx&logoColor=white)

# 📖 About
Dnsmasq WebConfig is a simple web-based tool that makes it
easy to configure your dnsmasq server without manually editing config files.
With simple and intuitive interface, you can easily create and manage:
* DNS records (A, AAAA, CNAME, MX, TXT, SRV)
* DHCP settings
* PXE boot configurations

# ✨ Features
- Mobile-friendly and responsive web interface
- Both light and dark theme

# 📦 Requirements

* A web server (e.g. Apache, Nginx) configured to run PHP
* PHP
* Dnsmasq
* Git (not needed on OpenWRT)
* unzip (for OpenWRT installations)

Package names differ between distros. 

|         	| Arch Linux                   	| Debian/Ubuntu                        	| OpenWRT    	|
|---------	|------------------------------	|--------------------------------------	|------------	|
| Apache  	| httpd                        	| apache2                              	| apache     	|
| Nginx   	| nginx                        	| nginx                                	| nginx-full 	|
| PHP     	| php-apache<br>php<br>php-fpm 	| libapache2-mod-php<br>php<br>php-fpm 	| php8-cgi   	|
| dnsmasq 	| dnsmasq                      	| dnsmasq                              	| dnsmasq    	|
| git     	| git                          	| git                                  	| -          	|
| unzip   	| -                            	| -                                    	| unzip      	|

You can set up this on OpenWRT with either Apache on Nginx but for simplicity, default uHTTPd will be used.


# ⚙️ Installation

> [!CAUTION]
> This tool should never be exposed to the public internet.
> The best practice is to restrict access to localhost or
> other trusted IPs only, to minimize security risks.

> [!NOTE]
> In this guide we assume that websites are stored under `/srv/http` directory
> (e.g. `/srv/http/site1`, `/srv/http/site2` etc.)


1. Go to your web server root directory
```bash
$ sudo mkdir -p /srv/http
$ cd /srv/http
```


2. Clone this repository to your server
```bash
$ sudo git clone https://github.com/ahenyao/dnsmasq-webconfig.git
```
For OpenWRT
```bash
# wget https://github.com/ahenyao/dnsmasq-webconfig/archive/refs/heads/main.zip -O /srv/http/dnsmasq-webconfig.zip
# unzip dnsmasq-webconfig.zip
# mv dnsmasq-webconfig-main dnsmasq-webconfig
```


3. Create a configuration directory
```bash
$ sudo mkdir /etc/dnsmasq.webconfig
```


4. Set correct ownership and permissions (skip if on OpenWRT)

    If you don't remember changing user or group in web server config go with:
    On Arch Linux use `http` as both user and group
    On Ubuntu/Debian use `www-data` as both

   (Apache) a. Find the user and group under which your web server runs

   For Arch Linux

   ```bash
   $ grep -iE "^User|^Group" /etc/httpd/conf/httpd.conf
   ```
   For Debian/Ubuntu

   ```bash
   $ grep -iE "^User|^Group" /etc/apache2/apache2.conf
   ```


   (Nginx) a. Find the user and group under which your web server runs

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
   $ sudo chown user:group /etc/dnsmasq.webconfig
   $ sudo chmod 755 /etc/dnsmasq.webconfig
   ```

   c. Allow user to restart dnsmasq (optional, not for OpenWRT)

   I think this isn't proper way to do it because it opens a security hole. (Please open issue if you know better way)


   Upon saving, new config is automatically used.
   So if access to web panel isn't authenticated or restricted 
   to IPs, anyone could add malicious DNS records. 
   Thus, someone could add redirects for domain X
   to its fake equivalent to get your credentials. 


   If you 100% trust people to whom you grant IP access, then go ahead. 
   Otherwise, I suggest running command below every time you save config.

   ```bash
   $ sudo systemctl restart dnsmasq
   ```
   
   If you're sure and want automatic restarts
   ```bash
   $ sudo visudo
   ```
   Add this, replacing `user` with the same thing as in 4b
   ```
   user ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart dnsmasq
   ```

5. Configure Dnsmasq

> [!WARNING]
> If you already have configurations in `dnsmasq.conf` run this first to back it up
```bash
$ sudo mv /etc/dnsmasq.conf /etc/dnsmasq.conf.old
```

All configurations for this tool are stored in the `/etc/dnsmasq.webconfig` directory, so we need to tell dnsmasq to look in this location
```bash
$ sudo nano /etc/dnsmasq.conf
```

Replace the file contents with
```
conf-dir=/etc/dnsmasq.webconfig/,*.conf
```
This will include all `*.conf` files from `/etc/dnsmasq.webconfig`


6. Configure web server

  > [!IMPORTANT]
  > Replace example.com with your domain.

## Apache example config

For Arch Linux
```bash
$ sudo nano /etc/httpd/conf/extra/httpd-vhosts.conf
```

For Debian/Ubuntu
```bash
$ sudo nano /etc/apache2/sites-available/dnsmasq-webconfig.conf
$ sudo a2ensite dnsmasq-webconfig.conf
```

Add this at the end of file
```
<VirtualHost *:80>
  ServerName dnsmasq.example.com
  DocumentRoot /srv/http/dnsmasq-webconfig # Replace with path to cloned repo
  <Directory /srv/http/dnsmasq-webconfig> # Same here
    Require local # this allows only from localhost
    #Require ip 10.20.30.40 # allow also from 10.20.30.40
  </Directory>
  ErrorLog "/var/log/dnsmasq-webconfig_error.log"
  CustomLog "/var/log/dnsmasq-webconfig_access.log" common
</VirtualHost>
```

## Nginx example config

```bash
$ sudo nano /etc/nginx/nginx.conf
```
Add this to http block
```
server {
    listen 80;
    root /srv/http/dnsmasq-webconfig; # Replace with path to cloned repo
    index index.php;

    location / {
        allow 127.0.0.0/8; # this allows only from localhost
        # allow 10.20.30.40; # allow also from 10.20.30.40
        deny all;
    }

    location ~ \.php$ {
        try_files $fastcgi_script_name =404;
        include fastcgi_params;
        fastcgi_pass            unix:/run/php-fpm/php-fpm.sock;
        fastcgi_index            index.php;
        fastcgi_buffers            8 16k;
        fastcgi_buffer_size        32k;
        fastcgi_param DOCUMENT_ROOT    $realpath_root;
        fastcgi_param SCRIPT_FILENAME    $realpath_root$fastcgi_script_name;
    }
    error_log /var/log/nginx/dnsmasq_webconfig_error.log;
    access_log /var/log/nginx/dnsmasq_webconfig_access.log;
}
```

## uHTTPd example config (OpenWRT)

```bash
# nano /etc/config/uhttpd
```

Add this to uHTTPd config file. We use 8080 as port because 80 is already taken by LuCI.
```
config uhttpd 'dnsmasqwebconfig'
        list 'listen_http' '0.0.0.0:8080'
        option 'home'        '/srv/http/dnsmasq-webconfig'
        list interpreter '.php=/usr/bin/php-cgi'
        option index_page 'index.php'
        option cgi_prefix '/cgi-bin'
```

```bash
# nano /etc/php.ini
```
Find `doc_root` line and set it to empty
```doc-root=```

7. Enabling and restarting services
```bash
$ sudo systemctl enable apache2            # or httpd on Arch
$ sudo systemctl enable nginx php-fpm      # if you're using Nginx
$ sudo systemctl enable dnsmasq

$ sudo systemctl restart apache2             # or httpd on Arch
$ sudo systemctl restart nginx php-fpm      # if you're using Nginx
$ sudo systemctl restart dnsmasq
```

If on OpenWRT
```bash
# uci commit uhttpd
# /etc/init.d/uhttpd restart
```

8. Setting up `hosts` file (on client PC)

> [!IMPORTANT]
> If you changed domain name at step 6, change it here too.
> Replace `10.20.30.40` with target machine's IP

It is recommended to let system know that when we visit `dnsmasq.example.com` it should show us dnsmasq-webconfig. This is done with `hosts` file.
```bash
$ sudo nano /etc/hosts
```
On Windows run Notepad or (preferably Notepad++) and open `C:\Windows\System32\Drivers\etc\hosts`

Add this to the end
```
10.20.30.40 dnsmasq.example.com
```

9. Testing

If everything was done correctly you should see panel when visiting http://dnsmasq.example.com (or your domain). On OpenWRT go to http://dnsmasq.example.com:8080

# 🚩 Opening Issues
If you find a bug or want to request some feature, feel free to open an issue
1. Check if it wasn't mentioned
2. If not, click on the "New issue" button
3. Describe issue or feature. Please include OS and dependencies versions and screenshots.

I will try to reach out as soon as I can

# 🪪 License

This project is licensed under the [GNU Affero General Public License v3.0 (AGPL-3.0)](https://www.gnu.org/licenses/agpl-3.0.html).
