<?php
/**
 *  Double validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sInteger.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Double validator.
 *  @package    Library
 *  @subpackage Validators
 */
class DoubleValidator extends IntegerValidator {
    
    /**
     *
     * Constructor.
     * @param   integer $min_value
     * @param   integer $max_value
     * @param   boolean $positive
     * @param   boolean $negative
     * @access  public 
     *
     */
    function __construct($min_value = 0.00, $max_value = 1000.00, $positive = true, $negative = true) {
        parent::__construct($min_value, $max_value, $positive, $negative);
        
        $this->min_value = floatval($this->min_value);
        $this->max_value = floatval($this->max_value);
    }
    
    /**
     *
     * Validates the integer value.
     * @param   integer $value
     * @return  boolean
     * @access  public 
     *
     */
    function validate($value, $force_cast = true) {
        
        $value = floatval($value);
        
        if (!is_float($value)) {
            $this->_error = 'error_message_for_value_is_not_an_float';
        	return false;
        }
        
        return parent::validate($value, false);
    }
}
?>