mule
====

Mule is a PHP framework for website development, based on Smarty, Idiorm + Paris and JanRain's OpenID library. It evolved as a common factor out of several websites I've developed over time. It's not state of the art, but it has a very simple structure.

Includes:

* OpenID authentication (one OpenID identity per account, no password recovery);
* a database patching system;
* session-based flash messages.

Installation
----

* Get a copy of the code:

        $ git clone https://github.com/CatalinFrancu/mule

* Some light local configuration:

        $ cd mule
        $ tools/setup.sh

* Create the database (adapt to suit your needs):

        $ mysql -u root -e 'create database mule charset utf8'

* Modify mule.conf to reflect your locale, database config etc.
* Apply any patches to bring the database schema to date:

        $ php tools/migration.php

* Depending on your HTTP server and installation path, you may need to enable and configure various modules: userdir, rewrite, PHP.
