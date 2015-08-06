![Screenshot](medias/screenshot.png)

####Demo

https://readerself.com/demo/?email=example@example.com&password=example

####Requirements
* PHP 5.2.4 or greater
* MySQL 5.0 or greater / SQLite
* Apache 2.2 or greater with mod_rewrite module enabled (and "Allowoverride All" in VirtualHost / Directory configuration to allow .htaccess file)

####Installation

Edit [/application/config/database.php](/application/config/database.php)
* with MySQL, define "username", "password" and "database" ("hostname" if necessary)
* with SQLite, set "hostname" with "sqlite:application/database/readerself.sqlite" and "dbdriver" with "pdo"

Launch in a browser to access setup

Add to cron (hourly)
```text
cd /path-to-installation && php index.php refresh items
```

####Update
Keep /application/config/database.php and /application/config/readerself_config.php
Replace all other files

####Third party

* [CodeIgniter](http://ellislab.com/codeigniter/)
* [SimplePie](http://simplepie.org)
* [jQuery](http://jquery.com/)
* [Material Design Lite](http://www.getmdl.io/)
* [FeedWriter](https://github.com/ajaxray/FeedWriter)
