![Screenshot](medias/screenshot.png)

####Demo

http://readerself.com/demo/
```text
example@example.com
example
```

####Requirements
* PHP 5.2.4 or greater
* MySQL 5.0 or greater / SQLite
* Apache 2.2 or greater with mod_rewrite module enabled

####Installation

Edit [/application/config/readerself_config.php](/application/config/readerself_config.php) to define "salt_password" (some letters and numbers to secure your password)

Edit [/application/config/database.php](/application/config/database.php)
* with MySQL, define "username", "password" and "database" ("hostname" if necessary)
* with SQLite, uncomment lines //$db['default']['hostname'] and //$db['default']['dbdriver'] = 'pdo';

With MySQL, load SQL commands from [/INSTALLATION.sql](/INSTALLATION.sql) in your database

Launch in a browser to register an account

Add to cron (hourly)
```text
cd /path-to-installation && php index.php refresh items
```

####Third party

* [CodeIgniter](http://ellislab.com/codeigniter/)
* [SimplePie](http://simplepie.org)
* [jQuery](http://jquery.com/)
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/)
* [UASparser](https://github.com/Synchro/UASparser)
* [FeedWriter](https://github.com/ajaxray/FeedWriter)
