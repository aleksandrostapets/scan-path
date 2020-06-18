SCAN PATH LIBRARY
-----------------
The library is used to get a list of files in a given category. When you find subdirectories, a list of files is also extracted from it.

REQUIREMENTS
------------
The minimum requirement by this project template that your Web server supports PHP 7.1.0.

INSTALLATION
------------
### Install via Composer
If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

~~~
composer require wherw/scan-path
~~~

CONFIGURATION
-------------
Create an instance of the ScanPath class

Create an instance of the ScanPath class. To search for files using this library, you need to call the setExtension method with a parameter in which you need to specify the array of file extensions you want to find. If you want to find files using mimeType, you need to call the setMimeType method, in which you need to specify the type of file you want to find. Supported types:
 - application; 
 - audio;
 - images;
 - multipart;
 - text;
 - video;

But keep in mind that this method takes much longer than the setExtension method

EXAMPLE
-------
~~~
$scan = new \wherw\ScanPath();
$scan->setPath('/mnt/music/');
$scan->setExtension([
    'm4a',
    'flac',
    'ogg',
    'mp3',
    'wma',
    'wav',
    'ape',
    'aac'
]);

$files = $scan->getFiles();
~~~
OR
~~~
$scan = new \wherw\ScanPath();
$scan->setPath('/mnt/music/');
$scan->setMimeType('audio');

$files = $scan->getFiles();
~~~