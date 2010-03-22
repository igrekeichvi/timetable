<?php

Fire_Loader::loadClass('DefaultAction', 'default', array(FIRE_APPLICATION_PATH . '/actions'), true);
Fire_Loader::loadClass('Fire_Form', 'Form', array(FIRE_LIBRARY_PATH));
Fire_Loader::helper('Form');

define('MANAGEMENT_TEMPLATE_BODY', 'management/body');

class ManagementDefaultAction extends DefaultAction {
    
    /**
     * Sessions
     * @var     Fire_Session $_session
     * @access  private 
     */
    var $_session;
    
    /**
     * Request generator
     * @var     Fire_RawRequest_Helper $_rq
     * @access  private 
     */
    var $_rq;
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
		$this->_session =& Fire_Session::instance();
        $this->_rq = Fire_Loader::helper('RawRequest', true);
    }
	
	/**
     *
     * Main management execution allow action.
     * @return  boolean
     * @access  public
     *
     */
    function permit() {
		
		//if (isset($this->_session->username) && isset($this->_session->password)) {
			
			$administrators = Fire_Loader::model('administrators', true, true);
			$session_result = $administrators->findAll(
				array('id'), 
				array(
					'`username` = ' . Fire_Sanitize::qstr(Fire_Sanitize::escapeStr($this->_session->username)), 
					'AND `password` = ' . Fire_Sanitize::qstr(Fire_Sanitize::escapeStr($this->_session->password))
				),
				1, ITEMS_UNLIMITED, array(), array());
			
			if (count($session_result) > 0) {
				return true;
			}
		//} 

		$this->redirect_route = $this->_rq->href($this->_config->get('router_action_parameter'), 'm_login');
		return false;
	
    }
    
    /**
     *
     * Performs the action.
     * @return  boolean
     * @access  public
     *
     */
    function perform() {
        
        $this->_view = new Fire_View('management/index', true, 'management/body');
        $this->setCommons();
        
    }
}
?>