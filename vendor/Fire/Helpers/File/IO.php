<?php
/**
 *  This file contains IO file class.
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
 *  Files input/ouput (reading/writing) class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_File_IO_Helper extends Fire_File_Helper {
    
    /**
     * @var integer $_mode
     * @access private 
     */
    var $_mode;
    
    /**
     * @var resource $_handler
     * @access private 
     */
    var $_handler;
    
    /**
     *
     * Constructor.
     * @param  string $file
     * @access public 
     *
     */
    function __construct($file = null) {
        parent::__construct($file);
    }
    
    /**
     *
     * Opens the file.
     * @param   string  $mode
     * @param   boolean $exit_on_fail
     * @access  public 
     *
     */
    function open($mode = 'r', $exit_on_fail = true) {
        $this->_mode = $mode;
        
        if (!$this->_handler = @fopen($this->_file, $this->_mode)) {
            Fire_Error::throwError(
                sprintf('Failed to open the file %s with mode %s',
                        $this->_file,
                        $this->_mode
                ), __FILE__, __LINE__, $exit_on_fail
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
        if (is_resource($this->_handler) && (get_resource_type($this->_handler) == 'stream')) {
        	return true;
        }
        return false;
    }
    
    /**
     *
     * Closes the file.
     * @access public 
     *
     */
    function close() {
        if (!@fclose($this->_handler)) {
            Fire_Error::throwError('Failed to open the file %s with mode %s', __FILE__, __LINE__);
        }
    }
    
    /**
     *
     * Checks for eof of file.
     * @return boolean
     * @access public
     *
     */
    
    function eof() {
        return (
            $this->checkResource() ?
            feof($this->_handler) :
            Fire_Error::throwError('File resource member is not a valid file resource.', __FILE__, __LINE__)
        );
    }
    
    /**
     *
     * Reads a data of $size from the file.
     * @param  integer $size
     * @return mixed
     * @access public 
     *
     */
    function read($size = 4096) {
        if (!$content = @fread($this->_handler, $size)) {
            Fire_Error::throwError(sprintf('Failed to read %d bytes data from "%s" file.',
                    $size, 
                    $this->_file
                ), __FILE__, __LINE__
            );
        }
        
        return $content;
    }
    
    /**
     *
     * Writes $data to the file.
     * @param  string $data
     * @access public 
     *
     */
    function write($data) {
        if (!@fwrite($this->_handler, $data, strlen($data))) {
        	Fire_Error::throwError(sprintf('Failed to write %s data to %s file.',
        	       $data,
        	       $this->_file
        	   ), __FILE__, __LINE__
        	);
        }
    }
    
    /**
     *
     * Truncates file to given length.
     * @param  integer $size
     * @access public 
     *
     */
    function truncate($size = 0) {
        if (!@ftruncate($this->_handler, $size)) {
            Fire_Error::throwError(sprintf('Failed to truncate file %s to size %d',
                    $this->_file,
                    $size
                ), __FILE__, __LINE__
            );
        }
    }
}
?>