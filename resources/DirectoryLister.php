<?php

/**
 * DirectoryLister is a simple file file listing script.
 */
class DirectoryLister {
    
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
                if ($file != ".") {
                    
                    // Get files relative and absolute path
                    $relativePath = $directory . '/' . $file;
                    
                    if (substr($relativePath, 0, 2) == './') {
                        $relativePath = substr($relativePath, 2);
                    }
                    
                    $realPath = realpath($relativePath);
                    
                    // Get file type
                    if (is_dir($realPath)) {
                        $fileType = 'directory';
                    } else {
                        
                        // Get file extension
                        $fileExt = pathinfo($realPath, PATHINFO_EXTENSION);
                    
                        if (isset($this->_settings['file_types'][$fileExt])) {
                            $fileType = $this->_settings['file_types'][$fileExt];
                        } else {
                            $fileType = 'unknown';
                        }
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
                            'file_type' => 'back'
                        );
                        
                    } else {
                        
                        // Add file info to the array
                        $directoryArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                            'file_path' => $relativePath,
                            'file_size' => $fileType == 'directory' ? '-' : round(filesize($realPath) / 1024) . 'KB',
                            'mod_time'  => date("Y-m-d H:i:s", filemtime($realPath)),
                            'file_type' => $fileType
                        );
                        
                    }
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