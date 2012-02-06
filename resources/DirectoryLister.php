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
    protected $_directory     = NULL;
    protected $_appDir        = NULL;
    protected $_appURL        = NULL;
    protected $_config      = NULL;
    protected $_systemMessage = NULL;
    
    
    /**
     * DirectoryLister construct function. Runs on object creation.
     */
    function __construct() {
        
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
        
        // Load the configuration file
        $configFile = $this->_appDir . '/config.php';
        
        if (file_exists($configFile)) {
            include($configFile);
        } else {
            $this->setSystemMessage('error', '<b>ERROR:</b> Unable to locate application config file');
        }
        
        // Set the config array to a global variable
        $this->_config = $config;
         
        // Get the directory path for listing
        if (!empty($_GET['dir'])) {
            $dir = $_GET['dir'];
        } else {
            $dir = '.';
        }
                
        // Remove trailing slash if present
        if(substr($dir, -1, 1) == '/') {
            $dir = substr($dir, 0, -1);
        }
                    
        // Prevent access to hidden files
        if (in_array(strtolower($dir), $this->_config['hidden_files'])) {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> Access denied');
            
            // Set the directory to web root
            $dir = '.';
        }
        
        // Prevent access to dotfiles if specified
        if ($this->_config['hide_dot_files']) {
            if (strlen($dir) > 1 && substr($dir, 0, 1) == '.') {
                // Set the error message
                $this->setSystemMessage('error', '<b>ERROR:</b> Access denied');
                
                // Set the directory to web root
                $dir = '.';
            }
        }
                
        // Check if file path exists
        if (!file_exists($dir)) {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> File path does not exist');
                
            // Set the directory to web root
            $dir = '.';
        }

        // Prevent access to parent folders
        if (strstr($dir, '<') || strstr($dir, '>') || strstr($dir, '..') || substr($dir, 0, 1) == '/') {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> An invalid path string was deceted');
                
            // Set the directory to web root
            $this->_directory = '.';
        } else {
            // Should stop all URL wrappers (Thanks to Hexatex)
            $this->_directory = $dir;
        }
        
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
        $dirArray = explode('/', $directory);

        // Statically set the Home breadcrumb        
        $breadcrumbsArray[] = array(
            'link' => $this->_appURL,
            'text' => 'Home'
        );
        
        // Generate breadcrumbs
        foreach ($dirArray as $key => $dir) {
            
            if ($dir != '.') {
                
                $link = $this->_appURL . '?dir=';
                
                for ($i = 0; $i <= $key; $i++) {
                    $link = $link . $dirArray[$i] . '/';
                }
                
                // Remove trailing slash
                if(substr($link, -1) == '/') {
                    $link = substr($link, 0, -1);
                }
                
                $breadcrumbsArray[] = array(
                    'link' => $link,
                    'text' => $dir
                );
                
            }
            
        }

        // print_r($breadcrumbsArray); die();
        
        // Return the breadcrumb array
        return $breadcrumbsArray;
    }

    /**
     * Gets path of the listed directory
     * 
     * @return string Pat of the listed directory
     * @acces public
     */
    public function getListedPath() {
        
        // Build the path
        if ($this->_directory == '.') {
            $path = $this->_appURL;
        } else {
            $path = $this->_appURL . $this->_directory;
        }
        
        // Return the path
        return $path;
    }
    
    
    /**
     * Get an array of error messages or false when empty.
     * 
     * @return array Array of error messages
     * @access public
     */
    public function getSystemMessages() {
        if (isset($this->_systemMessage) && is_array($this->_systemMessage)) {
            return $this->_systemMessage;
        } else {
            return false;
        }
    }
    
    
    /**
     * Loop through directory and return array with file info, including
     * file path, size, modification time, icon and sort order.
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
                    
                        if (isset($this->_config['file_types'][$fileExt])) {
                            $fileIcon = $this->_config['file_types'][$fileExt];
                        } else {
                            $fileIcon = 'blank.png';
                        }
                        
                        $sort = 2;
                    }
                    
                    if ($file == '..') {
                        
                        if ($this->_directory != '.') {
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
                        }
                        
                    } elseif (!in_array($file, $this->_config['hidden_files'])) {
                        
                        // Add all non-hidden files
                        if ($this->_directory == '.' && $file == 'index.php'
                        || $this->_config['hide_dot_files'] && substr($file, 0, 1) == '.') {
                            // This isn't the file you're looking for. Move along...
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
    
    
    /**
     * Add a message to the system message array
     * 
     * @param string $type The type of message (ie - error, success, notice, etc.)
     * @param string $message The message to be displayed to the user
     * @access public
     */
    public function setSystemMessage($type, $text) {

        // Create empty message array if it doesn't already exist
        if (isset($this->_systemMessage) && !is_array($this->_systemMessage)) {
            $this->_systemMessage = array();
        } 

        // Set the error message
        $this->_systemMessage[] = array(
            'type'  => $type,
            'text'  => $text
        );
        
        return true;
    }
    
}

?>