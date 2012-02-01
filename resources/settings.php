<?php

/**
 * Basic settings
 */

$hideDotFiles     = true;
$listFoldersFirst = true;
$listSort         = 'alphabetical';


/**
 * Cache settings
 */

// $cacheEnabled    = false;
// $cacheExpiration = 0;


/**
 * Hidden files
 */
 
$hiddenFiles = array();

$hiddenFiles[] = '.htaccess';
$hiddenFiles[] = '.htpasswd';
$hiddenFiles[] = 'resources';

/**
 * Icon settings
 */

$fileTypes = array(

    //Applications
    'app'   => 'app.png',
    'bat'   => 'app.png',
    'deb'   => 'app.png',
    'exe'   => 'app.png',
    'msi'   => 'app.png',
    'rpm'   => 'app.png',
    
    // Archives
    '7z'    => 'archive.png',
    'bz'    => 'archive.png',
    'gz'    => 'archive.png',
    'rar'   => 'archive.png',
    'tar'   => 'archive.png',
    'zip'   => 'archive.png',
    
    // Audio
    'aac'   => 'music.png',
    'mid'   => 'music.png',
    'midi'  => 'music.png',
    'mp3'   => 'music.png',
    'ogg'   => 'music.png',
    'wma'   => 'music.png',
    'wav'   => 'music.png',
    
    // Code
    'c'     => 'code.png',
    'cpp'   => 'code.png',
    'css'   => 'code.png',
    'erb'   => 'code.png',
    'htm'   => 'code.png',
    'html'  => 'code.png',
    'java'  => 'code.png',
    'js'    => 'code.png',
    'php'   => 'code.png',
    'pl'    => 'code.png',
    'py'    => 'code.png',
    'rb'    => 'code.png',
    'xhtml' => 'code.png',
    'xml'   => 'code.png',
    
    // Disc Images
    'cue'   => 'cd.png',
    'iso'   => 'cd.png',
    'mdf'   => 'cd.png',
    'mds'   => 'cd.png',
    'mdx'   => 'cd.png',
    'nrg'   => 'cd.png',
    
    // Documents
    'csv'   => 'excel.png',
    'doc'   => 'word.png',
    'docx'  => 'word.png',
    'odt'   => 'text.png',
    'pdf'   => 'pdf.png',
    'xls'   => 'excel.png',
    'xlsx'  => 'excel.png',
    
    // Images
    'bmp'   => 'image.png',
    'gif'   => 'image.png',
    'jpg'   => 'image.png',
    'jpeg'  => 'image.png',
    'png'   => 'image.png',
    'tga'   => 'image.png',
    
    // Scripts
    'bat'   => 'terminal.png',
    'cmd'   => 'terminal.png',
    'sh'    => 'terminal.png',
    
    // Text
    'log'   => 'text.png',
    'rtf'   => 'text.png',
    'txt'   => 'text.png',
    
    // Video
    'avi'   => 'video.png',
    'mkv'   => 'video.png',
    'mov'   => 'video.png',
    'mp4'   => 'video.png',
    'mpg'   => 'video.png',
    'wmv'   => 'video.png',
    'swf'   => 'flash.png',
    
    // Other
    'msg'   => 'message.png'
);
    
?>