# Free MongoDB GUI powered by PHP

Visually administrate your MongoDB database. Create, read, update & delete your documents.<br>
Query your MongoDB database with SELECT SQL statements. You can also create & drop indexes.<br>
Autocompletion is available for fields, MongoDB & SQL keywords via `Ctrl` `Space` combination.<br>
Additional features: Export documents to JSON. Import documents from JSON. Manage users.

Screenshots
-----------

![MongoDB PHP GUI - Visualize Database](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/screenshots/mpg-visualize-database.png)

![MongoDB PHP GUI - Query Database](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/screenshots/mpg-query-database.png)

![MongoDB PHP GUI - Manage Indexes](https://raw.githubusercontent.com/SamuelTS/MongoDB-PHP-GUI/master/docs/screenshots/mpg-manage-indexes.png)

Installation
------------

### Docker (PHP built-in server)
1. Clone current repository.
2. Run once `docker build -t mongodb-php-gui .` at project's root directory.
3. Run `docker run -it -p 5000:5000 mongodb-php-gui` at project's root directory.
4. Open your browser at this address: http://127.0.0.1:5000/ to access GUI.<br>
5. If your MongoDB is running on localhost, use 172.17.0.1 as host to login.

### Apache HTTP server
1. Clone current repository in Apache Web root directory or setup a virtual host.
2. Be sure to have PHP >= 7.2 with [MongoDB ext.](https://www.php.net/manual/en/mongodb.installation.php) enabled.
3. Check that `rewrite_module` module is enabled in your Apache configuration.
4. Be sure to have `AllowOverride All` in your Apache (virtual host) configuration.
5. Run `composer install` at project's root directory to install all PHP dependencies.
6. Optionnaly, if you want to query DB with SQL, you must have [Java JDK](https://jdk.java.net/) installed.
7. Open your browser at Apache server URL to access GUI.

Thanks
------

❤️ Thanks to [Limber](https://github.com/nimbly/Limber), [Capsule](https://github.com/nimbly/Capsule), [Font Awesome](https://fontawesome.com/), [Bootstrap](https://getbootstrap.com/), [CodeMirror](https://github.com/codemirror/codemirror) and [JsonView](https://github.com/pgrabovets/json-view).<br>
Thanks also to [MongoDB PHP library](https://github.com/mongodb/mongo-php-library), [vis.js](https://github.com/visjs) and [SQL to MongoDB Query Converter](https://github.com/vincentrussell/sql-to-mongo-db-query-converter). ❤️

Copyright
---------

© 2020 Samuel Tallet
