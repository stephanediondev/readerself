####Thank you to answer a few questions about your rss/atom/feed reader/aggregator

https://project29k.typeform.com/to/GFAwM1

####Demo

https://readerself.com/demo/?email=example@example.com&password=example

####Requirements
* PHP 5.2.4 or greater
* MySQL 5.0 or greater / SQLite
* Apache 2.2 or greater with mod_rewrite module enabled (and "Allowoverride All" in VirtualHost / Directory configuration to allow .htaccess file)

####Installation

Launch in a browser to access setup

Add to cron (hourly)
```text
cd /path-to-installation && php index.php refresh items
```

####Update before August 11, 2015
* keep /application/config/database.php and /application/config/readerself_config.php
* download the latest version and replace all other files

####Update after August 11, 2015
* go to settings / update
* click on the version not installed (if any)

####Third party

* [CodeIgniter](http://ellislab.com/codeigniter/)
* [SimplePie](http://simplepie.org)
* [jQuery](http://jquery.com/)
* [Material Design Lite](http://www.getmdl.io/)
* [FeedWriter](https://github.com/ajaxray/FeedWriter)

####Screenshots

![Desktop](https://readerself.com/medias/home.png)
![Mobile](https://readerself.com/medias/moto-g-2014.png)
