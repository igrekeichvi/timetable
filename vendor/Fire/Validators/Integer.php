<?php
/**
 *  Integer validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Integer validator.
 *  @package    Library
 *  @subpackage Validators
 */
class IntegerValidator extends Fire_Validator {
    
    /**
     * @var     integer $min_value
     * @access  public 
     */
    var $min_value;
    
    /**
     * @var     integer $max_value
     * @access  public 
     */
    var $max_value;
    
    /**
     * @var     boolean $positive
     * @access  public 
     */
    var $positive;
    
    /**
     * @var     boolean $negative
     * @access  public 
     */
    var $negative;
    
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
    function __construct($min_value = 0, $max_value = 1000, $positive = true, $negative = true) {
        parent::__construct();
        
        $this->min_value = intval($min_value);
        $this->max_value = intval($max_value);
        $this->positive = $positive;
        $this->negative = $negative;
    }
    
    /**
     *
     * Validates the integer value.
     * @param   integer $value
     * @param   boolean $force_cast
     * @return  boolean
     * @access  public 
     *
     */
    function validate($value, $force_cast = true) {
        
        if ($force_cast) {
        	$value = intval($value);
        
            if (!is_integer($value)) {
                $this->_error = 'error_message_for_value_is_not_an_integer';
            	return false;
            }
        }
        
        if ($value < $this->min_value) {
        	$this->_error = 'error_message_for_value_is_lesser';
        	return false;
        }
        
        if ($value > $this->max_value) {
        	$this->_error = 'error_message_for_value_is_bigger';
        	return false;
        }
        
        if (!$this->positive && $value > 0) {
        	$this->_error = 'error_message_for_value_is_positive';
        	return false;
        }
        
        if (!$this->negative && $value < 0) {
        	$this->_error = 'error_message_for_value_is_negative';
        	return false;
        }
        
        return true;
    }
}
?>