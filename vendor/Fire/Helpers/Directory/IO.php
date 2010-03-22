<?php
/**
 *  This file contains DirectoryIO class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');

/** Loads file. */
require_once(sprintf('%1$s%2$sHelpers%2$sFile.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Directories input/ouput (reading/writing) class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Directory_IO_Helper extends Fire_File_Helper {
    
    /**
     * @var resource $__handle
     * @access private 
     */
    var $_handle;
    
    /**
     *
     * Constructor.
     * @param   string  $file
     * @access  public 
     *
     */
    function __construct($file = null) {
        if (!Fire_File_Helper::isDirectory($file)) {
        	Fire_Error::throwError( sprintf('%s is not a valid directory name.',
                    $file
        	   ), __FILE__, __LINE__
        	);
        }
        
        parent::__construct($file);
    }
    
    /**
     *
     * Opens the directory for reading.
     * @access public 
     *
     */
    function open() {
        if ($this->isDirectory($this->_file)) {
        	if (!$this->__handle = @opendir($this->_file)) {
        	    Fire_Error::throwError(sprintf('Can\'t open "%s" directory for reading.',
        	           $this->_file
        	       ), __FILE__, __LINE__
        	    );
        	}
        } else {
            Fire_Error::throwError(sprintf('The "%s" is not a valid directory.',
        	       $this->_file
        	   ), __FILE__, __LINE__
        	);
        }
    }
    
    /**
     *
     * Performs a check if member resource is valid or not.
     * @return boolean
     * @access public 
     *
     */
    function checkResource() {
        if (is_resource($this->__handle) && (get_resource_type($this->__handle) == 'stream')) {
        	return true;
        }
        return false;
    }
    
    /**
     *
     * Reads the directory contents.
     * @access public 
     *
     */
    function read() {
        return (
            $this->checkResource() ?
            readdir($this->__handle) :
            Fire_Error::throwError(sprintf('Resource is not valid directory resource.'
                ), __FILE__, __LINE__
            )
        );
    }
    
    /**
     *
     * Closes the directory.
     * @access public 
     *
     */
    function close() {
        closedir($this->__handle);
    }
    
    /**
     *
     * Adds a content to the collection.
     * @return mixed $contents
     * @access public 
     *
     */
    function readContents() {
        $contents = array();
        $this->open();
        while ($file = $this->read()) {
            if ($file != '.' && $file != '..') {
            	array_push($contents, $file);
            }
        }
        $this->close();
        return $contents;
    }
}
?>