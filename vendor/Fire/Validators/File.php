<?php
/**
 *  File upload validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  File upload validator.
 *  @package    Library
 *  @subpackage Validators
 */
class FileValidator extends Fire_Validator {
    
    /**
     * @var     mixed $types
     * @access  public 
     */
    var $mime_types;
    
    /**
     * @var     mixed $extensions
     * @access  public 
     */
    var $extensions;
    
    /**
     * @var     integer $max_size
     * @access  public 
     */
    var $max_size;
    
    /**
     * @var     string $target_directory
     * @access  public 
     */
    var $target_directory;
    
    /**
     *
     * Constructor.
     * @param   mixed   $file_types
     * @param   mixed   $extensions
     * @param   integer $max_size
     * @param   string  $target_directory
     * @return  
     * @access  public 
     *
     */
    function __construct($mime_types = array(), $extensions = array(), $max_size = 2134016, $target_directory = '') {
        parent::__construct();
        
        $this->mime_types = $mime_types;
        $this->extensions = $extensions;
        $this->max_size = intval($max_size);
        $this->target_directory = $target_directory;
    }
    
    /**
     *
     * Validates a file upload.
     * @param   string  $key_of_files_array
     * @return  boolean
     * @access  public 
     *
     */
    function validate($key_of_files_array) {
        
        if (!isset($_FILES[$key_of_files_array])) {
        	return;
        }
        
        $_file_to_check = $_FILES[$key_of_files_array];
        
        if (empty($_file_to_check) || empty($_file_to_check['name'])) {
        	$this->_error = 'error_message_for_missing_file';
        	return false;
        }
        
        if ($_file_to_check['error'] != 0) {
        	$this->_error = 'error_message_for_upload_file_failed';
        	return false;
        }
        
        if (!empty($this->mime_types) && !in_array($_file_to_check['type'], $this->mime_types)) {
        	$this->_error = 'error_message_for_disallowed_mime_type';
        	return false;
        }
        
        if (!empty($this->extensions) && !in_array(strtolower(substr(strrchr($_file_to_check['name'], '.'), 1)), $this->extensions)) {
        	$this->_error = 'error_message_for_disallowed_extension';
        	return false;
        }
        
        if ($_file_to_check['size'] > $this->max_size) {
        	$this->_error = 'error_message_for_file_size_limit_overload';
        	return false;
        }
        
        if (!empty($this->target_directory) && (!is_dir($this->target_directory) || !is_writable($this->target_directory))) {
        	$this->_error = 'error_message_for_target_directory_not_writeable';
        	return false;
        }
        
        return true;
    }
}
?>