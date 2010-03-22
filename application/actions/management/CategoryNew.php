<?php

Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);

class ManagementCategoryNewAction extends ManagementDefaultAction {
    
    function __construct() {
        parent::__construct();
    }
    
    function validate() {
        
        Fire_Loader::loadClass('Fire_Form', 'Form', array(FIRE_LIBRARY_PATH));
        $this->_form = new Fire_Form();
        
        $this->_form->register('name', new StringValidator(1));
        $this->_form->register('description', new StringValidator(1));
        
        if ($this->_form->validate($this->_input->getPost())) {
            
            $categories = Fire_Loader::model('categories', true, true);
            if ($categories->unique('name', $this->_form->getValue('name'))) {
                return true;
            }
            
            $this->_form->setError('name', $this->_lc->get('caption_for_name_should_be_unique'));
        }
        
        $this->_view = new Fire_View('management/categoryadd', true, 'management/body');
        
        Fire_Loader::helper('Form');
        $this->_view->set('form', new Fire_Form_Helper($this->_form, $this->_lc));
        
        $this->setCommons();
        
        return false;
    }
    
    function perform() {
        
        $categories = Fire_Loader::model('categories', true, true);
        $categories->insert(
            array(
                'name'          =>  Fire_Sanitize::escapeStr($this->_form->getValue('name')),
                'description'   =>  Fire_Sanitize::escapeStr($this->_form->getValue('description'))
            )
        );
        
        $this->redirect_route = $this->_rq->href($this->_config->get('router_action_parameter'), 'm_categories');
    }
}

?>