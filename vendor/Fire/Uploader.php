<?php
/**
 * Uploader class.
 * @package     Library
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 * This file contains configuration reader and write class.
 * @package     Library
 */
class Fire_Uploader extends Fire_Object {
    
    /**
     * @var     mixed $_file
     * @access  private
     */
    var $_file;
    
    /**
     * @var     string $_target_directory
     * @access  private 
     */
    var $_target_directory;
    
    /**
     *
     * Constructor.
     * @param   string  $key_from_files_array
     * @access  public 
     *
     */
    function __construct($key_from_files_array = '', $target_directory = '') {
        parent::__construct();
        
        if (strlen($key_from_files_array) && isset($_FILES[$key_from_files_array])) {
        	$this->_file = $_FILES[$key_from_files_array];
        }
        
        if (strlen($target_directory)) {
        	$this->_target_directory = $target_directory;
        }
    }
    
    /**
     *
     * Sets the file.
     * @param   string  $key_from_files_array
     * @access  public 
     *
     */
    function setFile($key_from_files_array) {
        if (isset($_FILES[$key_from_files_array])) {
        	$this->_file = $_FILES[$key_from_files_array];
        }
    }
    
    /**
     *
     * Sets the target directory.
     * @param   string  $target_directory
     * @access  public 
     *
     */
    function setDirectory($target_directory) {
        $this->_target_directory = $target_directory;
    }
    
    /**
     *
     * Reset the uploader, remove store file info.
     * @access  public 
     *
     */
    function reset() {
        $this->_file = array();
    }
    
    /**
     *
     * Checks the file for error.
     * @return  boolean
     * @access  public 
     *
     */
    function check() {
        if ($this->_file['error'] == 0) {
        	return true;
        } else {
            return false;
        }
    }
    
    /**
     *
     * Performs uploading of the file.
     * @param   string $upload_to_directory
     * @param   boolean $keep_original_name
     * @return  mixed
     * @access  public 
     *
     */
    function upload($keep_original_name = false) {
        if (!$this->check()) {
            return false;
        }
        	
        $file_name = $this->_file['name'];
        if (!$keep_original_name) {
        	$file_name = $this->generate($this->getExtension());
        }
        
    	$res = move_uploaded_file(
    	   $this->_file['tmp_name'], 
    	   sprintf('%s%s', 
    	       $this->_target_directory, 
    	       $file_name
	       )
       );
            
    	if (!$res) {
    	    $this->reset();
    		return false;
    	}
        	
        return $file_name;
    }
    
    /**
     *
     * Deletes a file.
     * @param   string  $file_to_delete
     * @access  public 
     *
     */
    function delete($file_to_delete = '') {
        if (!strlen($file_to_delete)) {
        	$file_to_delete = $this->_target_directory . $this->_file['name'];
        }
        unlink($file_to_delete);
    }
    
    /**
     *
     * Returns file extension.
     * @return  string
     * @access  public 
     *
     */
    function getExtension() {
        return substr(strrchr($this->_file['name'], '.'), 1);
    }
    
    /**
     *
     * Generates unique name for the file.
     * @param   string  $extension
     * @return  string
     * @access  public 
     *
     */
    function generate($extension) {
        $name = sprintf('%s_%s.%s',
            time(),
            rand(1, 999),
            $extension
        );
        return $name;
    }
    
    /**
     *
     * Converts MB or KB in bytes.
     * @param   integer $size
     * @return  mixed
     * @access  public
     * @static 
     *
     */
    function convertInBytes($size)	{
        if (eregi("^[0-9]{1,}M$", $size))	{
            return $size * 1048576;
        } elseif (eregi("^[0-9]{1,}K$",	$size)) {
            return $size * 1024;
        } elseif (eregi("^[0-9]{1,}$", $size)) {
            return $size;
        } else {
            return false;
        }
    }
}
?>