<?php
/**
 *  Boolean validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Boolean validator.
 *  @package    Library
 *  @subpackage Validators
 */
class BooleanValidator extends Fire_Validator {
    
    /**
     * @var     boolean $must_choose
     * @access  public 
     */
    var $must_choose;
    
    /**
     *
     * Constructor.
     * @param   boolean $must_choose
     * @access  public 
     *
     */
    function __construct($must_choose = false) {
        parent::__construct();
        $this->must_choose = $must_choose;
    }
    
    /**
     *
     * Validates the boolean field.
     * @param   mixed   $value
     * @param   boolean $must_choose
     * @return  boolean
     * @access  public 
     *
     */
    function validate($value) {
        
        if ($this->must_choose && empty($value)) {
            $this->_error = 'error_message_for_must_choose_a_value';
        	return false;
        }
        
        return true;
    }
}
?>