<?php
/**
 * This file contains base File class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/** Defining the directory seprator, dependable on which OS PHP is running. */
if (!defined('DIRECTORY_SEPARATOR')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
        define('DIRECTORY_SEPARATOR', '\\');
    } else {
        /** @ignore */
        define('DIRECTORY_SEPARATOR', '/');
    }
}

/**
 * Files base class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_File_Helper extends Fire_Object {
    
    /**
     * @var string $_file
     * @access private 
     */
    var $_file;
    
    /**
     * @var     mixed $_attributes
     * @access  private 
     */
    var $_attributes;
    
    /**
     *
     * Constructor.
     * @param  string $file
     * @access public 
     *
     */
    function __construct($file = null) {
        parent::__construct();
        
        if (is_string($file)) {
            $this->set($file);
        }
    }
    
    /**
     *
     * Sets the files, and performs some checks for it.
     * @param   string  $file
     * @param   boolean $with_attributes
     * @access  public 
     *
     */
    function set($file, $with_attributes = true) {
        $this->_file = $file;
        
        if ($with_attributes === true) {
        	$this->_attributes['size'] = filesize($this->_file);
        	$this->_attributes['permission'] = substr(sprintf('%o', fileperms($this->_file)), -4);
        	$this->_attributes['owner'] = fileowner($this->_file);
        	$this->_attributes['group'] = filegroup($this->_file);
        	$this->_attributes['last_access_time'] = fileatime($this->_file);
        	$this->_attributes['creation_time'] = filectime($this->_file);
        	$this->_attributes['last_modified_time'] = filemtime($this->_file);
        }
    }
    
    /**
     *
     * Returns current file.
     * @return string
     * @access public 
     *
     */
    function get() {
        return isset($this->_file) ? $this->_file : '';
    }
    
    /**
     *
     * Returns attribute.
     * @param   string  $attribute_name
     * @return  mixed
     * @access  public 
     *
     */
    function getAttribute($attribute_name) {
        return isset($this->_attributes[$attribute_name]) ? $this->_attributes[$attribute_name] : null;
    }
    
    /**
     *
     * Checks if the file exists. Can be used as static if $file parameter is given.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function exists($file = null) {
        clearstatcache();
        return ($file ? file_exists($file) : file_exists($this->_file));
    }
    
    /**
     *
     * Checks if file is readable. Can be used as static if $file parameter is present.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function isReadable($file = null) {
        return ($file ? is_readable($file) : is_readable($this->_file));
    }
    
    /**
     *
     * Checks if file is writable. Can be used as static if $file parameter is present.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function isWritable($file = null) {
        return ($file ? is_writable($file) : is_writable($this->_file));
    }
    
    /**
     *
     * Checks if file is a directory. Can be used as static if $file parameter is present.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function isDirectory($file = null) {
        return ($file ? is_dir($file) : is_dir($this->_file));
    }
    
    /**
     *
     * Checks if file is a file. Can be used as static if $file parameter is present.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function isFile($file = null) {
        return ($file ? is_file($file) : is_file($this->_file));
    }
    
    /**
     *
     * Returns base name of the file.
     * @param  string $file
     * @return string
     * @access public 
     *
     */
    function getName($file = null) {
        return ($file ? basename($file) : basename($this->_file));
    }
    
    /**
     *
     * Returns directory part from the path of the file.
     * @param  string $file
     * @return string
     * @access public 
     *
     */
    function getDirectory($file = null) {
        return ($file ? dirname($file) : dirname($this->_file));
    }
    
    /**
     *
     * Returns instance of IO class depending of type of the file.
     * @param   string  $file
     * @return  Fire_File_IO_Helper|Fire_Directory_IO_Helper
     * @access  public 
     *
     */
    function io($file) {
        
        $_type = Fire_File_Helper::isDirectory($file) ? 'Directory' : 'File';
        
    	require_once(
            sprintf('%1$s%2$s%3$s%2$sIO.php', 
                dirname(__FILE__), 
                DIRECTORY_SEPARATOR, 
                $_type
            )
    	);
    	
    	$_class_name = sprintf('Fire_%s_IO_Helper', $_type);
        return new $_class_name($file);
    }
    
    /**
     *
     * Returns instance of System class depending of type of the file.
     * @param   string  $file
     * @return  Fire_File_System_Helper|Fire_Directory_System_Helper
     * @access  public 
     *
     */
    function system($file) {
        
        $_type = Fire_File_Helper::isDirectory($file) ? 'Directory' : 'File';
        
    	require_once(
            sprintf('%1$s%2$s%3$s%2$sSystem.php', 
                dirname(__FILE__), 
                DIRECTORY_SEPARATOR, 
                $_type
            )
    	);
    	
    	$_class_name = sprintf('Fire_%s_System_Helper', $_type);
        return new $_class_name($file);
    }
}
?>