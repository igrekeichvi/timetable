<?php
/**
 *  This is the top action.
 *  @package    Library
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Top action class.
 *  @package    Library
 */
class Fire_Action extends Fire_Object {
    
    /**
     * Dynamic!
     * @var     Fire_Locale $_lc
     * @access  private 
     */
    var $_lc;
    
    /**
     * Dynamic!
     * @var     Fire_Model $_model
     * @access  private 
     */
    var $_model;
    
    /**
     * Dynamic!
     * @var     Fire_Form $_form
     * @access  private 
     */
    var $_form;
    
    /** Bellows are common! */
    
    /**
     * @var     Fire_Input $_input
     * @access  private 
     */
    var $_input;
    
    /**
     * @var     Fire_View $_view
     * @access  private 
     */
    var $_view;
    
    /**
     * @var     string $forward_route
     * @access  public 
     */
    var $forward_route;
    
    /**
     * @var     string $redirect_route
     * @access  public 
     */
    var $redirect_route;
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        
        $this->_input =& Fire_Input::instance();
    }
    
    /**
     *
     * Checks if this action is allowed to be executed.
     * @return  boolean
     * @access  public 
     *
     */
    function permit() {
        return true;
    }
    
    /**
     *
     * Validate method.
     * @access  public 
     *
     */
    function validate() {
        return true;
    }
    
    /**
     *
     * Perform method.
     * @access  public 
     *
     */
    function perform() {
        Fire_Error::throwError('Action::perform() not implemented!', __FILE__, __LINE__);
    }
    
    /**
     *
     * Returns the view results.
     * @return  string
     * @access  public 
     *
     */
    function getView() {
        return $this->_view->render();
    }
}
?>