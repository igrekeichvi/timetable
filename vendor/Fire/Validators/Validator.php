<?php
/**
 *  Validator interface.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads object */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Validator interface.
 *  @package    Library
 *  @subpackage Validators
 */
class Fire_Validator extends Fire_Object {
    
    /**
     * @var     string $_error
     * @access  private 
     */
    var $_error;
    
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
     * Returns error.
     * @return  string
     * @access  public 
     *
     */
    function getError() {
        return isset($this->_error) ? $this->_error : '';
    }
    
    /**
     *
     * Validates the item.
     * @param   mixed   $value
     * @access  public 
     *
     */
    function validate($value) {
        Fire_Error::throwError('Fire_Validator::validate() must be implemented by child classes.', __FILE__, __LINE__);
    }
}
?>