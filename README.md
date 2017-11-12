#### Requirements

##### Language
* PHP 5.3 or greater

##### Database
* MySQL 5.1 or greater
* SQLite

##### Web server
* Apache 2.2 or greater with mod_rewrite module enabled (and "Allowoverride All" in VirtualHost / Directory configuration to allow .htaccess file)
* Nginx with rewrite rules https://github.com/readerself/readerself/issues/59

#### Installation

Launch in a browser to access setup

Add to cron (hourly)
```text
cd /path-to-installation && php index.php refresh items
```

#### Update before August 11, 2015
* keep /application/config/database.php and /application/config/readerself_config.php
* download the latest version and replace all other files

#### Update after August 11, 2015
* go to settings / update
* click on the version not installed (if any)

#### Third party

* [CodeIgniter](http://ellislab.com/codeigniter/)
* [SimplePie](http://simplepie.org)
* [jQuery](http://jquery.com/)
* [Material Design Lite](http://www.getmdl.io/)
* [FeedWriter](https://github.com/ajaxray/FeedWriter)

#### Screenshots

![Desktop](https://readerself.com/medias/home.png)
![Mobile](https://readerself.com/medias/moto-g-2014.png)
