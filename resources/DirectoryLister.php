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
    protected $_themeName     = 'bootstrap';
    protected $_directory     = NULL;
    protected $_appDir        = NULL;
    protected $_appURL        = NULL;
    protected $_config        = NULL;
    protected $_systemMessage = NULL;
    
    
    /**
     * DirectoryLister construct function. Runs on object creation.
     */
    public function __construct() {
        
        // Set class directory constant
        if(!defined('__DIR__')) {
            define('__DIR__', dirname(__FILE__));
        }
        
        // Set application directory
        $this->_appDir = __DIR__;
        
        // Build the application URL
        $this->_appURL = $this->_getAppUrl();
        
        // Load the configuration file
        $configFile = $this->_appDir . '/config.php';
        
        // Set the config array to a global variable
        if (file_exists($configFile)) {
            $this->_config = require_once($configFile);
        } else {
            $this->setSystemMessage('error', '<b>ERROR:</b> Unable to locate application config file');
        }
        
        // Set the theme name
        $this->_themeName = $this->_config['theme_name'];
        
        // Set the directory global variable
        $this->_directory = $this->_setDirecoryPath(@$_GET['dir']);
        
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
     * Returns the theme name.
     * 
     * @access public
     */
    public function getThemeName() {
        // Return the theme name
        return $this->_config['theme_name'];
    }
    
    /**
     * Returns the path to the chosen theme directory.
     * 
     * @param bool $absolute Wether or not the path returned is absolute (default = false).
     * @access public
     */
    public function getThemePath($absolute = false) {
        if ($absolute) {
            // Set the theme path
            $themePath = $this->_appDir . '/themes/' . $this->_themeName;
        } else {
            // Get relative path to application dir
            $realtivePath = $this->_getRelativePath(getcwd(), $this->_appDir);
            
            // Set the theme path
            $themePath = $realtivePath . '/themes/' . $this->_themeName;
        }
        
        return $themePath;
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
     * Validates and returns the directory path
     * 
     * @return string Directory path to be listed
     * @access private
     */
    private function _setDirecoryPath($dir) {
        
        // Check for an empty variable
        if (empty($dir) || $dir == '.') {
            return '.';
        }
        
        // Eliminate double slashes
        while (strpos($dir, '//')) {
            $dir = str_replace('//', '/', $dir);
        }
        
        // Remove trailing slash if present
        if(substr($dir, -1, 1) == '/') {
            $dir = substr($dir, 0, -1);
        }
        
        // Verify file path exists and is a directory
        if (!file_exists($dir) || !is_dir($dir)) {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> File path does not exist');
                
            // Return the web root
            return '.';
        }
                    
        // Prevent access to hidden files
        if ($this->_isHidden($dir)) {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> Access denied');
            
            // Set the directory to web root
            return '.';
        }

        // Prevent access to parent folders
        if (strpos($dir, '<') !== false || strpos($dir, '>') !== false 
        || strpos($dir, '..') !== false || strpos($dir, '/') === 0) {
            // Set the error message
            $this->setSystemMessage('error', '<b>ERROR:</b> An invalid path string was deceted');
                
            // Set the directory to web root
            return '.';
        } else {
            // Should stop all URL wrappers (Thanks to Hexatex)
            $directoryPath = $dir;
        }
        
        // Return
        return $directoryPath;
    }
    
    
    /**
     * Loop through directory and return array with file info, including
     * file path, size, modification time, icon and sort order.
     * 
     * @return array An array of the directory contents
     * @access private
     */
    private function _readDirectory($directory, $sort = 'natcase') {
        
        // Initialize array
        $directoryArray = array();
        
        // Get directory contents
        $files = scandir($directory);
        
        // Read files/folders from the directory
        foreach ($files as $file) {
            
            if ($file != '.') {
                
                // Get files relative path
                $relativePath = $directory . '/' . $file;
                
                if (substr($relativePath, 0, 2) == './') {
                    $relativePath = substr($relativePath, 2);
                }
                
                // Get files absolute path
                $realPath = realpath($relativePath);
                
                // Determine file type by extension
                if (is_dir($realPath)) {
                    $iconClass = 'icon-folder';
                    $sort = 1;
                } else {
                    // Get file extension
                    $fileExt = pathinfo($realPath, PATHINFO_EXTENSION);
                
                    if (isset($this->_config['file_types'][$fileExt])) {
                        $iconClass = $this->_config['file_types'][$fileExt];
                    } else {
                        $iconClass = $this->_config['file_types']['blank'];
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
                            'file_path'  => $this->_appURL . $directoryPath,
                            'file_size'  => '-',
                            'mod_time'   => date('Y-m-d H:i:s', filemtime($realPath)),
                            'icon_class' => 'icon-up-dir',
                            'sort'       => 0
                        );
                    }
                    
                } elseif (!$this->_isHidden($relativePath)) {
                    
                    // Add all non-hidden files to the array
                    if ($this->_directory != '.' || $file != 'index.php') {
                        $directoryArray[pathinfo($realPath, PATHINFO_BASENAME)] = array(
                            'file_path'  => $relativePath,
                            'file_size'  => is_dir($realPath) ? '-' : round(filesize($realPath) / 1024) . 'KB',
                            'mod_time'   => date('Y-m-d H:i:s', filemtime($realPath)),
                            'icon_class' => $iconClass,
                            'sort'       => $sort
                        );
                    }
                    
                }
            }

        }
 

        // Sort the array
        $sortedArray = $this->_arraySort($directoryArray, $this->_config['list_sort_order']);
        
        // Return the array
        return $sortedArray;

    }


    /**
    * Sorts an array by the provided sort method.
    *
    * @param array $array Array to be sorted
    * @param string $sort Sorting method (acceptable inputs: natsort, natcasesort, etc.)
    * @return array
    * @access private
    */
    private function _arraySort($array, $sortMethod) {
        // Create empty array
        $sortedArray = array();
        
        // Create new array of just the keys and sort it
        $keys = array_keys($array);
        
        switch ($sortMethod) {
            case 'asort':
                asort($keys);
                break;
            case 'arsort':
                arsort($keys);
                break;
            case 'ksort':
                ksort($keys);
                break;
            case 'krsort':
                krsort($keys);
                break;
            case 'natcasesort':
                natcasesort($keys);
                break;
            case 'natsort':
                natsort($keys);
                break;
            case 'shuffle':
                shuffle($keys);
                break;
        }
        
        // Loop through the sorted values and move over the data
        
        if ($this->_config['list_folders_first']) {
            
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
            
        } else {
            
            foreach ($keys as $key) {
                if ($array[$key]['sort'] == 0) {
                    $sortedArray[$key] = $array[$key];
                }
            }
            
            foreach ($keys as $key) {
                if ($array[$key]['sort'] > 0) {
                    $sortedArray[$key] = $array[$key];
                }
            }

        }
        
        // Return sorted array
        return $sortedArray;
        
    }
    
    
    /**
     * Determines if a file is supposed to be hidden
     * 
     * @access private
     */
    private function _isHidden($filePath) {
        
        // Define the OS specific directory separator
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        
        // Convert the file path to an array
        $pathArray  = explode(DS, $filePath);
        
        // Return true if dotfile and access to dotfiles is prohibited
        if ($this->_config['hide_dot_files']) {
            foreach ($pathArray as $element) {
                if (strlen($element) > 1 && substr($element, 0, 1) == '.') {
                    return true;
                }
            }
        }
        
        // Compare path array to all hidden file paths
        foreach ($this->_config['hidden_files'] as $hiddenPath) {
            
            // Strip trailing slash if present
            if (substr($hiddenPath, -1) == DS) {
                $hiddenPath = substr($hiddenPath, 0, -1);
            }
            
            // Convert the hidden file path to an array
            $hiddenArray = explode(DS, $hiddenPath);
            
            // Calculate intersections between the path and hidden arrays
            $intersect = array_intersect_assoc($pathArray, $hiddenArray);
            
            // Return true if the intersect matches the hidden array
            if ($intersect == $hiddenArray) {
                return true;
            }
            
        }
        
        return false;
        
    }

    /**
     * Builds the root application URL from server variables.
     * 
     * @return string The application URL
     * @access private
     */
    private function _getAppUrl() {
        
        // Get the server protocol
        if (isset($_SERVER['HTTPS'])) {
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
        $appUrl = $protocol . $host . $path;
        
        // Return the URL
        return $appUrl;
    }
    
    /**
     * Compares two paths and returns the relative path from one to the other
     * 
     * @param string $fromPath Starting path
     * @param string $toPath Ending path
     * @return string $relativePath
     * @access private
     */
    private function _getRelativePath($fromPath, $toPath) {
        
        // Define the OS specific directory separator
        if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
        
        // Remove double slashes from path strings
        $fromPath   = str_replace(DS . DS, DS, $fromPath);
        $toPath     = str_replace(DS . DS, DS, $toPath);
        
        // Explode working dir and cache dir into arrays
        $fromPathArray  = explode(DS, $fromPath);
        $toPathArray    = explode(DS, $toPath);
        
        // Remove last fromPath array element if it's empty
        $x = count($fromPathArray) - 1;
        
        if(!trim($fromPathArray[$x])) {
            array_pop($fromPathArray);
        }
        
        // Remove last toPath array element if it's empty
        $x = count($toPathArray) - 1;
        
        if(!trim($toPathArray[$x])) {
            array_pop($toPathArray);
        }
        
        // Get largest array count
        $arrayMax = max(count($fromPathArray), count($toPathArray));
        
        // Set some default variables
        $diffArray  = array();
        $samePath   = true;
        $key        = 1;
        
        // Generate array of the path differences
        while ($key <= $arrayMax) {
            if (@$toPathArray[$key] !== @$fromPathArray[$key] || $samePath !== true) {
                
                // Prepend '..' for every level up that must be traversed
                if (isset($fromPathArray[$key])) {
                    array_unshift($diffArray, '..');
                }
                
                // Append directory name for every directory that must be traversed  
                if (isset($toPathArray[$key])) {
                    $diffArray[] = $toPathArray[$key];
                } 
                
                // Directory paths have diverged
                $samePath = false;
            }
            
            // Increment key
            $key++;
        }

        // Set the relative thumbnail directory path
        $relativePath = implode('/', $diffArray);
        
        // Return the relative path
        return $relativePath;
        
    }
    
}

?>
