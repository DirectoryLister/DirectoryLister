Directory Lister - The simple PHP directory lister
==================================================
Created by, [Chris Kankiewicz](http://www.ChrisKankiewicz.com)
Contributions from Tampa, veso266 & others
Original fork from mijorepusic


Introduction
------------

Directory Lister is a simple PHP script that lists the contents of any web-accessible directory and
allows navigating there within. Simply upload Directory Lister to any directory and get immediate
access to all files and sub-directories under that directory. Directory Lister is written in PHP and JavaScript.

Distributed under the [MIT License](http://www.opensource.org/licenses/mit-license.php).


Features
--------

  * Extremely simple installation, no database, additional servers, just drop the files and configure
  * Creates on-the-fly listing of any web-accessible directory
  * Customizable sort order of files/folders
  * Easily define hidden files to be excluded from the listing
  * Download of entire directories as zip
  * Various sorting options
  * Directory size information
  * File hashing
  * Custom directory descriptions(place .desc file in directory)
  * Download counter


Requirements
------------

Directory Lister requires PHP 5.3+ to work properly.  For more information on PHP, please visit
<http://www.php.net>. It is recommended to use latest PHP 7.2+ if possible.

A linux/unix host is needed to support all features.

For directory zip functionality zip library needs to be present in /usr/bin/zip


Installation
------------

 1. Make sure all requirements are met
 2. Clone or download the repo
 3. Place all files in the parent-most directory you wish DirectoryLister to use as home
 4. Rename `default.config.php` to `config.php` and configure DirectoryLister as needed within, (remove `.desc` in main directory to remove welcome message)
 5. Files and folders to be shown can now be placed alongside


Troubleshooting
---------------

Ensure you have the latest version of Directory Lister installed.

Verify that you have PHP 5.3 or later installed. You can verify your PHP version by running:

    php --version
	

Open up an issue if you need anything.


License
-------

Directory Lister is distributed under the terms of the
[MIT License](http://www.opensource.org/licenses/mit-license.php).
Copyright 2017 [Chris Kankiewicz](http://www.chriskankiewicz.com)
Copyright 2019 Tampa
