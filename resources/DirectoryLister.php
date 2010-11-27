<?php

/**
 * DirectoryLister is a simple file file listing script.
 */
class DirectoryLister {
    
    // Set some default variables
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
        
        // Get hidden files and add them to 
        // $this->_hiddenFiles = $this->_readHiddenFiles();
    }
    
    /**
     * DirectoryLister destruct function. Runs on object destruction.
     */
    function __destruct() {
        // NULL
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
                if ($file != "." && $file != "..") {
                    
                    // Get files relative and absolute path
                    $relativePath = $directory . '/' . $file;
                    
                    if (substr($relativePath, 0, 2) == './') {
                        $relativePath = substr($relativePath, 2);
                    }
                    
                    $realPath = realpath($relativePath);
                    
                    // Add file info to the array
                    $directoryArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                        'file_path' => $relativePath,
                        'file_size' => round(filesize($realPath) / 1024),
                        'mod_time'  => date("Y-m-d H:i:s", filemtime($realPath))
                    );
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        // Return the file array
        return $directoryArray;
        
    }
    
    /**
     * Loop through directory and return array with pertinent information
     */
    protected function _readDirectory($directory, $sort = 'natcase') {
        
    }
    
}

?>