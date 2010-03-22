<?php

Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);

class ManagementLoginAction extends ManagementDefaultAction {
    
    function __construct() {
        parent::__construct();
    }
    
    function permit() {
		
        if (parent::permit()) {
            $this->redirect_route = $this->_rq->href($this->_config->get('router_action_parameter'), 'm_default');
            return false;
        }
        
        $this->redirect_route = null;
        
        return true;
    }
    
    function validate() {
        
        Fire_Loader::loadClass('Fire_Form', 'Form', array(FIRE_LIBRARY_PATH));
        $this->_form = new Fire_Form();
        
        $this->_form->register('username', new StringValidator(1));
        $this->_form->register('password', new StringValidator(1));
		
        if ($this->_form->validate($this->_input->getPost())) {
			
            $administrators = Fire_Loader::model('administrators', true, true);
			$session_result = $administrators->findAll(
				array('id'), 
				array(
					'`username` = ' . Fire_Sanitize::qstr(Fire_Sanitize::escapeStr($this->_form->getValue('username'))),
					'AND `password` = ' . Fire_Sanitize::qstr(Fire_Sanitize::escapeStr(md5($this->_form->getValue('password'))))
				),
				1, ITEMS_UNLIMITED, array(), array());
		
			if (count($session_result) > 0) {
			  
                $this->_session->username = $this->_form->getValue('username');
                $this->_session->password = md5($this->_form->getValue('password'));
				
                $this->_session->write();
				return true;
			}
			
			$this->_form->setError('username', $this->_lc->get('caption_for_login_failed'));
            
        }
		
        $this->_view = new Fire_View('management/login');
        
        Fire_Loader::helper('Form');
        $this->_view->set('form', new Fire_Form_Helper($this->_form, $this->_lc));
       
        $this->setCommons();
        
        return false;
    }
    
    function perform() {
        $this->redirect_route = $this->_rq->href($this->_config->get('router_action_parameter'), 'm_default');
    }
}
?>