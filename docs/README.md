# Free MongoDB GUI powered by PHP

Visually administrate your MongoDB database. Create, read and delete operations are supported.

Screenshots
-----------

![MongoDB PHP GUI](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/mpg.png)

Installation
------------

1. `git clone` current repository somewhere in the cloud or on your local machine.<br>
**Warning: If you choose cloud option. Be sure to secure folder with a *.htpasswd*.**
2. Be sure to have PHP >= 7 with [MongoDB extension](https://www.php.net/manual/en/mongodb.installation.php) enabled in this environment.
3. Run `composer install` at project's root directory to install all PHP dependencies.

Configuration
-------------

Open *config.php* file located at project's root directory. Edit following constants values:

`MPG_MONGODB_USER`, `MPG_MONGODB_PASSWORD`, `MPG_MONGODB_HOST` and `MPG_MONGODB_PORT`.

Thanks
------

This application is based on [Limber](https://github.com/nimbly/Limber), [Capsule](https://github.com/nimbly/Capsule), [MongoDB PHP library](https://github.com/mongodb/mongo-php-library) and [JsonView](https://github.com/pgrabovets/json-view). ❤️

Copyright
---------

© 2020 Samuel Tallet
