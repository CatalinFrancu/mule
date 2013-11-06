mule
====

Mule is a PHP framework for website development, based on Smarty, Idiorm + Paris and JanRain's OpenID library. It evolved as a common factor out of several websites I've developed over time. It's not state of the art, but it has a very simple structure.

Includes:

* OpenID authentication (one OpenID identity per account, no password recovery);
* a database patching system;
* session-based flash messages.


Installation
------------

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

* Depending on your HTTP server and installation path, you may need to enable and configure various modules: userdir, rewrite, PHP. Suggested changes (for Ubuntu / Linux Mint, everything as sudo):
  * Install mod-php5.

          # apt-get install libapache2-mod-php5

  * Enable the rewrite module. This is only used to hide .php extensions in URLs

          # a2enmod rewrite

  * Enable the userdir module if you're working under ~/public_html/

          # a2enmod userdir

  * Modify the relevant config file to allow .htaccess files:

          # for userdir installations the file is usually /etc/apache2/mods-available/userdir.conf
          <Directory /home/*/public_html>
              ...
              AllowOverride All
              ...
          </Directory>
          
          # for document root installations the file is usually /etc/apache2/sites-available/default
          <Directory /var/www/>
              ...
              AllowOverride All
              ....
          </Directory>

  * Restart Apache:

          # /etc/init.d/apache2 restart


Localization (for users)
------------------------

To use Mule in your language, you need three things:

* Install the locale you want on your system
* Make sure there is a corresponding entry in the locale/ directory. Note that the names must match exactly. For instance, if your system has a ro_RO locale, but the locale/ directory has a ro_RO.utf8 subdirectory, localization won't work.
* Someone actually needs to do the translation work for your language. You can be that person! :-) See below for contributions.


Localization (for contributors)
-------------------------------


We'd like to keep up with localization. You don't need to do the translation yourself, but we'd appreciate it if you tagged the strings that need be localized.

* In PHP, just add a call to gettext around all literal strings. So instead of 

        return 'OpenID login failed';

  please say

        return _('OpenID login failed');

* In Smarty templates, we're relying on the trick that Smarty templates are eventually compiled to PHP files and poedit / xgettext can pick up on those. So just use the "_" variable modifier around string literals. We recommend doing this at sentence level:

        {"String to be localized"|_}

You can use sprintf in both PHP and Smarty so you can localize parametric sentences:

        {"User %s obtained %d points."|_|sprintf:"John":"100"}

Note that this syntax does not allow for changes in argument orders between languages. We'll try to live with it.

To make sure that your strings get picked up by the translation tool, please follow these steps:

* Exercise all the affected pages so that the Smarty templates get compiled;
* Install poedit
* Run poedit locale/ro_RO.utf8/LC_MESSAGES/messages.po
* Run Catalog -> Update from sources
* Make sure that all your new strings show up
* Optionally save the catalog to make sure we get the new strings (we run poedit ourselves every now and then)
