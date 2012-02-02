<?php

/**
 * A simple PHP based directory lister that lists the contents
 * of a directory and all it's sub-directories and allows easy
 * navigation of the files within.
 *
 * This software distributed under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
 *
 * More info available at http://www.directorylister.com
 *
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2012 Chris Kankiewicz
 */
class DirectoryLister {
    
    // Define application version
    const VERSION = '2.0.0-dev';
    
    // Set some default variables
    protected $_directory   = NULL;
    protected $_appDir      = NULL;
    protected $_appURL      = NULL;
    protected $_settings    = NULL;
    
    
    /**
     * DirectoryLister construct function. Runs on object creation.
     */
    function __construct() {
        
        // Set the directory to list
        if (@$_GET['dir']) {
            $this->_directory = $_GET['dir'];
        } else {
            $this->_directory = '.';
        }
        
        // Prevent access to parent folders
        if (substr_count($this->_directory,'.',0,1) !== 0
        || substr_count($this->_directory,'<') !== 0
        || substr_count($this->_directory,'>') !== 0
        || substr_count($this->_directory,'/',0,1) !== 0) {
            $this->_directory = '.';
        }else{
            // Should stop all URL wrappers (Thanks to Hexatex)
            $this->_directory = './' . $this->_directory;
        }
        
        // Remove trailing slash if present
        if(substr($this->_directory, -1, 1) == '/') {
            $this->_directory = substr($this->_directory, 0, -1);
        }

        // Set class directory constant
        if(!defined('__DIR__')) {
            define('__DIR__', dirname(__FILE__));
        }
        
        // Set application directory
        $this->_appDir = __DIR__;
        
        // Get the server protocol
        if ($_SERVER['HTTPS']) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
        
        // Get the server hostname
        $host = $_SERVER['HTTP_HOST'];
        
        // Get the URL path
        $pathParts = pathinfo($_SERVER['PHP_SELF']);
        $path      = $pathParts['dirname'];
        
        // Ensure the path ends with a forward slash
        if (substr($path, -1) != '/') {
            $path = $path . '/';
        }
        
        // Build the application URL
        $this->_appURL = $protocol . $host . $path;
        
        // Get file settings
        $configFile = $this->_appDir . '/settings.php';
        
        if (file_exists($configFile)) {
            include($configFile);
        } else {
            die('ERROR: Unable to locate config');
        }
        
    }
    
    
    /**
     * Special init method for simple one-line interface.
     * 
     * @access public
     */
    public static function init() {
        $reflection = new ReflectionClass(__CLASS__);
        return $reflection->newInstanceArgs(func_get_args());
    }
    
    
    /**
     * Creates the directory listing and returns the formatted XHTML
     * 
     * @param string $path Relative path of directory to list
     */
    public function listDirectory($directory = NULL) {
        
        // Set directory varriable if left blank
        if ($directory === NULL) {
            $directory = $this->_directory;
        }
        
        // Get the directory array
        $directoryArray = $this->_readDirectory($directory);

        // Return the array
        return $directoryArray;
    }
    

    /**
     * Description...
     * 
     * @access public
     */
    public function listBreadcrumbs($directory = NULL) {
        
        // Set directory varriable if left blank
        if ($directory === NULL) {
            $directory = $this->_directory;
        }
        
        // Explode the path into an array
        $dirArray = explode($directory);
        
        print_r($dirArray); die();
        
        // Return the breadcrumb array
        // return $breadcrumbsArray;
    }
    
    
    /**
     * Loop through directory and return array with pertinent information
     * 
     * @access private
     */
    protected function _readDirectory($directory, $sort = 'natcase') {
        
        // Instantiate image array
        $directoryArray = array();
        
        // TODO: Sorting
        if ($handle = opendir($directory)) {
            
            while (false !== ($file = readdir($handle))) {
                if ($file != ".") {
                    
                    // Get files relative path
                    $relativePath = $directory . '/' . $file;
                    
                    if (substr($relativePath, 0, 2) == './') {
                        $relativePath = substr($relativePath, 2);
                    }
                    
                    // Get files absolute path
                    $realPath = realpath($relativePath);
                    
                    // Determine file type by extension
                    if (is_dir($realPath)) {
                        $fileIcon = 'folder.png';
                        $sort = 1;
                    } else {
                        // Get file extension
                        $fileExt = pathinfo($realPath, PATHINFO_EXTENSION);
                    
                        if (isset($this->_settings['file_types'][$fileExt])) {
                            $fileIcon = $this->_settings['file_types'][$fileExt];
                        } else {
                            $fileIcon = 'blank.png';
                        }
                        
                        $sort = 2;
                    }
                    
                    if ($file == '..') {
                        // Get parent directory path
                        $pathArray = explode('/', $relativePath);
                        unset($pathArray[count($pathArray)-1]);
                        unset($pathArray[count($pathArray)-1]);
                        $directoryPath = implode('/', $pathArray);
                        
                        if (!empty($directoryPath)) {
                            $directoryPath = '?dir=' . $directoryPath;
                        }
                        
                        // Add file info to the array
                        $directoryArray['..'] = array(
                            'file_path' => $this->_appURL . $directoryPath,
                            'file_size' => '-',
                            'mod_time'  => date("Y-m-d H:i:s", filemtime($realPath)),
                            'icon'      => 'back.png',
                            'sort'      => 0
                        );
                    } else {
                        // Add file info to the array
                        $directoryArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                            'file_path' => $relativePath,
                            'file_size' => is_dir($realPath) ? '-' : round(filesize($realPath) / 1024) . 'KB',
                            'mod_time'  => date("Y-m-d H:i:s", filemtime($realPath)),
                            'icon'      => $fileIcon,
                            'sort'      => $sort
                        );
                    }
                }
            }
            
            // Close open file handle
            closedir($handle);
        }

        // Sort the array
        $sortedArray = $this->_sortArray($directoryArray);
        
        // Return the array
        return $sortedArray;

    }


    /**
     * Description...
     * 
     * @access private
     */
    protected function _sortArray($array) {
        // Create empty array
        $sortedArray = array();
        
        // Create new array of just the keys and sort it
        $keys = array_keys($array); 
        natcasesort($keys);
        
        // Loop through the sorted values and move over the data
        foreach ($keys as $key) {
            if ($array[$key]['sort'] == 0) {
                $sortedArray[$key] = $array[$key];
            }
        }
        
        foreach ($keys as $key) {
            if ($array[$key]['sort'] == 1) {
                $sortedArray[$key] = $array[$key];
            }
        }

        foreach ($keys as $key) {
            if ($array[$key]['sort'] == 2) {
                $sortedArray[$key] = $array[$key];
            }
        }
        
        // Return the array
        return $sortedArray;
    }
    
}

?>