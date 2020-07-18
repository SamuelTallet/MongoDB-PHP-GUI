# Free MongoDB GUI powered by PHP

Visually administrate your MongoDB database. Create, read, update and delete your documents.<br>
Autocompletion is available for collection fields and MongoDB keywords via `Ctrl` + `Space` keys.

Screenshots
-----------

![MongoDB PHP GUI](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/screenshots/mpg-database-query.png)

![MongoDB PHP GUI](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/screenshots/mpg-collection-indexes.png)

Installation
------------

1. `git clone` current repository somewhere in the cloud or on your local machine.<br>
**Warning: If you choose cloud option. Be sure to secure folder with a *.htpasswd*.**
2. Be sure to have PHP >= 7 with [MongoDB extension](https://www.php.net/manual/en/mongodb.installation.php) enabled in this environment.
3. Run `composer install` at project's root directory to install all PHP dependencies.

Configuration
-------------

Open *config.php* file located at project's root directory. Edit `MPG_MONGODB*` constants.

Thanks
------

[Limber](https://github.com/nimbly/Limber), [Capsule](https://github.com/nimbly/Capsule), [MongoDB](https://github.com/mongodb/mongo-php-library), [Font Awesome](https://fontawesome.com/), [Bootstrap](https://getbootstrap.com/), [CodeMirror](https://github.com/codemirror/codemirror) and [JsonView](https://github.com/pgrabovets/json-view). ❤️

Copyright
---------

© 2020 Samuel Tallet
