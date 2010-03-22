<?php
/**
 *  Text validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads string validator. */
require_once(sprintf('%1$s%2$sValidators%2$sString.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Text validator.
 *  @package    Library
 *  @subpackage Validators
 */
class TextValidator extends StringValidator {
    
    /**
     *
     * Constructor.
     * @param   integer $min_lenght
     * @param   integer $max_lenght
     * @access  public 
     *
     */
    function __construct($min_lenght = 0, $max_lenght = 3000) {
        parent::__construct($min_lenght, $max_lenght);
    }
}
?>