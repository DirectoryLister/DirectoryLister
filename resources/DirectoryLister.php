<?php

/**
 * A simple PHP based directory lister that lists a directory and all
 * it's sub-directories and allows you to navigate there within.
 *
 * This software is dual liscensed under the following licenses:
 *     MIT License http://www.directorylister.com/COPYING-MIT.txt
 *     GPL Version 3 http://www.directorylister.com/COPYING-GPL.txt
 *
 * More info available at http://www.directorylister.com
 *
 * @author Chris Kankiewicz (http://www.chriskankiewicz.com)
 * @copyright 2011 Chris Kankiewicz
 */
class DirectoryLister {
    
    // Define application version
    const VERSION = '2.0.0-dev';
    
    // Set some default variables
    protected $_settings    = NULL;
    protected $_directory   = NULL;
    protected $_hiddenFiles = NULL;
    
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
        
        // Remove trailing slash if present
        if(substr($this->_directory, -1, 1) == '/') {
            $this->_directory = substr($this->_directory, 0, -1);
        }

        // Set class directory constant
        if(!defined('__DIR__')) {
            $iPos = strrpos(__FILE__, '/');
            define('__DIR__', substr(__FILE__, 0, $iPos) . '/');
        }
        
        // Get file settings
        $this->_settings = parse_ini_file(__DIR__ . '/settings.ini', true);
                
        // Get hidden files and add them to 
        // $this->_hiddenFiles = $this->_readHiddenFiles();
    }
    
    /**
     * Special init method for simple one-line interface.
     * @access public
     */
    public static function init() {
        $reflection = new ReflectionClass(__CLASS__);
        return $reflection->newInstanceArgs(func_get_args());
    }
    
    /**
     * Creates the directory listing and returns the formatted XHTML
     * @param string $path Relative path of directory to list
     */
    public function listDirectory($directory = NULL) {
        
        // Set directory varriable if left blank
        if ($directory === NULL) {
            $directory = $this->_directory;
        }
        
        // Instantiate image array
        $directoryArray = array();
        
        // TODO: Sorting
        if ($handle = opendir($directory)) {
            
            while (false !== ($file = readdir($handle))) {
                if ($file != ".") {
                    
                    // Get files relative and absolute path
                    $relativePath = $directory . '/' . $file;
                    
                    if (substr($relativePath, 0, 2) == './') {
                        $relativePath = substr($relativePath, 2);
                    }
                    
                    $realPath = realpath($relativePath);
                    
                    // Get file type
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
                        
                        // Add file info to the array
                        $directoryArray['..'] = array(
                            'file_path' => $directoryPath,
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
     * Loop through directory and return array with pertinent information
     */
    protected function _readDirectory($directory, $sort = 'natcase') {
        
    }
                            
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