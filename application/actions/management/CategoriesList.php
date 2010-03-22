<?php

Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);

class ManagementCategoriesListAction extends ManagementDefaultAction {
    
    function __construct() {
        parent::__construct();
    }
    
    function perform() {
        
        $categories = Fire_Loader::model('categories', true, true);
        $list_categories = $categories->findAll(
            array('id', 'name'),
            array(),
            is_null($this->_input->get('page')) ? 1 : $this->_input->get('page')
        );
        
        $this->_view = new Fire_View('management/categorieslist', true, 'management/body');
        $this->_view->set('list_categories', $list_categories);
        $this->setCommons();
    }
}
?>