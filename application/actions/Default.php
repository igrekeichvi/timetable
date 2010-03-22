<?php
Fire_Loader::loadClass('Fire_Sanitize', 'Sanitize', array(FIRE_LIBRARY_PATH));
class DefaultAction extends Fire_Action {
    
    /**
     * Configuration
     * @var     Fire_Config $_config
     * @access  private 
     */
    var $_config;
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        
        $this->_config =& Fire_Config::instance();
        
        Fire_Loader::library('Locale');
        $this->_lc =& Fire_Locale::instance('bg');
    }
    
    /**
     *
     * Set some commonly used objects.
     * @return  boolean
     * @access  public
     *
     */
    function setCommons() {
        
        $this->_view->setAsRef('lc', $this->_lc);
        $this->_view->setAsRef('rq', $this->_rq);
        $this->_view->setAsRef('cfg', $this->_config);
        $this->_view->set('router_action_parameter', $this->_config->get('router_action_parameter'));
        $markupHelper = Fire_Loader::helper('Markup', true);
        $this->_view->setAsRef('ml', $markupHelper);
        $this->_view->set('message_flash', $this->_lc->get($this->_input->get('_message_flash')));
        
    }
    
    /**
     *
     * Performs the action.
     * @return  boolean
     * @access  public
     *
     */
    function perform() {
        die('Not implemented');
    }
    
    function setRedirectRoute($redirectRoute) {
        $this->redirect_route = Fire_Sanitize::reverse($redirectRoute);
    }
}
?>