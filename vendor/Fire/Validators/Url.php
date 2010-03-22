<?php
/**
 *  URL address validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads string validator. */
require_once(sprintf('%1$s%2$sValidators%2$sString.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  URL address validator.
 *  @package    Library
 *  @subpackage Validators
 */
class UrlValidator extends StringValidator {
    
    /**
     *
     * Constructor.
     * @param   integer $min_lenght
     * @param   integer $max_lenght
     * @access  public 
     *
     */
    function __construct($min_lenght = 0, $max_lenght = 255) {
        parent::__construct($min_lenght, $max_lenght);
    }
    
    /**
     *
     * Validates given value for email rules.
     * @param   string  $value
     * @return  boolean
     * @access  public 
     *
     */
    function validate($value) {
        if (!parent::validate($value)) {
        	return false;
        }
        
        if (!preg_match('#^((http|https)\\:\\/\\/)?[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $value)) {
        	$this->_error = 'error_message_for_is_not_valid_url_address';
        	return false;
        }
        
        return true;
    }
}
?>