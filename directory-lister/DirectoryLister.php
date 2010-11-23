<?php

/**
 * DirectoryLister is a simple file file listing script.
 */
class DirectoryLister {
    
    // Set some default variables
    protected $_directoryPath   = '.';
    protected $_hiddenFiles     = NULL;
    
    /**
     * DirectoryLister construct function. Runs on object creation.
     */
    function __construct() {
                
        // Add trailing slash if none present
        if(substr($this->_directoryPath,-1,1) === '/') {
            $this->_directoryPath = substr_replace($this->_directoryPath, '', -1, 1);
        }
        
        // Get hidden files and add them to 
        // $this->_hiddenFiles = $this->_readHiddenFiles();
        
        print_r($this->listDirectory());
    }
    
    /**
     * DirectoryLister destruct function. Runs on object destruction.
     */
    function __destruct() {
        echo "<br/>" . PHP_EOL . "END OF LINE"; // TODO: Remove me
    }
    
    /**
     * Creates the directory listing and returns the formatted XHTML
     * @param string $path Relative path of directory to list
     */
    public function listDirectory($path = NULL) {
        
        // Set directory path if specified
        if ($path === NULL) {
            $path = $this->_directoryPath;
        }
        
        return $this->_readDirectory($path);
        
    }
    
    /**
     * Loop through directory and return array with pertinent information
     */
    protected function _readDirectory($directory, $sort = 'natcase') {
        
        // Instantiate image array
        $directoryArray = array();
        
        // TODO: Sorting
        if ($handle = opendir($directory)) {
            
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    
                    // Get files real path
                    $realPath = realpath($directory . '/' . $file);
                    
                    // Add file info to the array
                    $directoryArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                        'file_size' => round(filesize($realPath) / 1024),
                        'mod_time'  => date("Y-m-d H:i:s", filemtime("$realPath"))
                    );
                }
            }
            
            // Close open file handle
            closedir($handle);
            
        }
        
        // Return the file array
        return $directoryArray;
    }
    
}

?>