<?php

    // Include the DirectoryLister class
    require_once('resources/DirectoryLister.php');

    // Initialize the DirectoryLister object
    $lister = new DirectoryLister();

    // Restrict access to current directory
    ini_set('open_basedir', getcwd());

    // Return file hash
    if (isset($_GET['hash'])) {

        // Get file hash array and JSON encode it
        $hashes = $lister->getFileHash($_GET['hash']);
        $data   = json_encode($hashes);

        // Return the data
        die($data);

    }

    if (isset($_GET['zip'])) {

        $dirArray = $lister->zipDirectory($_GET['zip']);

    } else {

        // Initialize the directory array
        if (isset($_GET['dir'])) {
            if(isset($_GET['by'])){
                if(isset($_GET['order'])){
                    $dirArray = $lister->listDirectory($_GET['dir'],$_GET['by'],$_GET['order']);
                } else {
                    $dirArray = $lister->listDirectory($_GET['dir'],$_GET['by'],'asc');
                }
            } else {
                $dirArray = $lister->listDirectory($_GET['dir'],'name', 'asc');
            }
        } else {
            if(isset($_GET['by'])){
                if(isset($_GET['order'])){
                    $dirArray = $lister->listDirectory('.',$_GET['by'],$_GET['order']);
                } else {
                    $dirArray = $lister->listDirectory('.',$_GET['by'],'asc');
                }
            } else {
                $dirArray = $lister->listDirectory('.','name', 'asc');
            }
        }

        // Define theme path
        if (!defined('THEMEPATH')) {
            define('THEMEPATH', $lister->getThemePath());
        }

        // Set path to theme index
        $themeIndex = $lister->getThemePath(true) . '/index.php';

        // Initialize the theme
        if (file_exists($themeIndex)) {
            include($themeIndex);
        } else {
            die('ERROR: Failed to initialize theme');
        }

    }
