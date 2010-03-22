<?php
/**
 *  This file contains log class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/** Loads error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');

/**
 *  Logs class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Log_Helper extends Fire_Object {
    
    /**
     * @var     Fire_File_Helper $_log
     * @access  private 
     */
    var $_log_handle;
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
    }
    
    /**
     *
     * Creates a log file.
     * @param   string  $log_file
     * @access  public 
     *
     */
    function start($log_file) {
        if (!file_exists($log_file)) {
        	if (!$this->_log_handle = @fopen($log_file, 'w+')) {
        	    Fire_Error::throwError(sprintf('Failed to create log file "%s"', $log_file), __FILE__, __LINE__);
        	}
        } else {
            if (!$this->_log_handle = @fopen($log_file, 'a+')) {
                Fire_Error::throwError(sprintf('Failed to open log file "%s" for appending.', $log_file), __FILE__, __LINE__);
            }
        }
    }
    
    /**
     *
     * Writes a data to log file.
     * @param   string  $message
     * @access  public 
     *
     */
    function log($message) {
        if (!@fwrite($this->_log_handle, $message)) {
        	Fire_Error::throwError('Failed to write to log file.', __FILE__, __LINE__);
        }
    }
    
    /**
     *
     * Ends the logging session.
     * @access  public 
     *
     */
    function end() {
        fclose($this->_log_handle);
    }
}
?>