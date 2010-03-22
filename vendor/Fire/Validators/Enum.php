<?php
/**
 *  Enum validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Enum validator.
 *  @package    Library
 *  @subpackage Validators
 */
class EnumValidator extends Fire_Validator {
    
    /**
     * @var     mixed   $range
     * @access  public 
     */
    var $range;
    
    /**
     * @var     boolean $multiple
     * @access  public 
     */
    var $multiple;
    
    /**
     *
     * Constructor.
     * @param   mixed   $range
     * @param   boolean $multiple
     * @access  public 
     *
     */
    function __construct($range = array(), $multiple = false) {
        parent::__construct();
        
        $this->range = $range;
        $this->multiple = $multiple;
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
        
        if ((is_array($value) && empty($value))
            || (is_string($value) && !strlen($value))
        ) {
        	$this->_error = 'error_message_for_value_is_empty';
        	return false;
        }
        
        if (is_array($value)) {
            
            if (!$this->multiple) {
            	$this->_error = 'error_message_for_value_not_allowed_multiple_choises';
                return false;
            }
            
            foreach ($value as $_value_match) {
            	if (!in_array($_value_match, $this->range)) {
            		$this->_error = 'error_message_for_value_not_found_in_allowed_range';
            		return false;
            	}
            }
            
        } else {
            
            if (!in_array($value, $this->range)) {
        		$this->_error = 'error_message_for_value_not_found_in_allowed_range';
        		return false;
        	}
            
        }
        
        return true;
    }
}
?>