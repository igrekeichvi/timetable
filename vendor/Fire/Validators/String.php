<?php
/**
 *  String validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  String validator.
 *  @package    Library
 *  @subpackage Validators
 */
class StringValidator extends Fire_Validator {
    
    /**
     * @var     integer $min_length
     * @access  public 
     */
    var $min_length;
    
    /**
     * @var     integer $max_length
     * @access  public 
     */
    var $max_length;
    
    /**
     *
     * Constructor.
     * @param   integer $min_length
     * @param   integer $max_length
     * @access  public 
     *
     */
    function __construct($min_lenght = 0, $max_lenght = 255) {
        parent::__construct();
        
        $this->min_length = intval($min_lenght);
        $this->max_length = intval($max_lenght);
    }
    
    /**
     *
     * Validates given string.
     * @param   string  $value
     * @return  boolean
     * @access  public
     *
     */
    function validate($value) {
        $value = utf8_decode(trim($value));
        
        if (!strlen($value)) {
            $this->_error = 'error_message_for_string_is_empty';
        	return false;
        }
        
        if (strlen($value) < $this->min_length) {
        	$this->_error = 'error_message_for_string_has_less_characters_than_allowed';
        	return false;
        }
        
        if (strlen($value) > $this->max_length) {
        	$this->_error = 'error_message_for_string_has_more_characters_than_allowed';
        	return false;
        }
        
        return true;
    }
}
?>