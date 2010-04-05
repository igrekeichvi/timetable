<?php
Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);
define('OPERATORS_MODEL_NAME', 'operators');
class ManagementOperatorNewAction extends ManagementDefaultAction {
    
    private $_operators;
    
    function __construct() {
        parent::__construct();
        $this->_operators = Fire_Loader::model(OPERATORS_MODEL_NAME, true, true);
    }
    
    function validate() {
        $this->_form = new Fire_Form();
        $languages = $this->_config->get('languages');
        while(list(,$language) = each($languages)) {
            $this->_form->register(sprintf('name_%s', $language), new StringValidator(1, 50));
        }
        if ($this->_form->validate($this->_input->getPost(true))) {  
            $conditionsMatched = true;
            reset($languages);
            while(list(,$language) = each($languages)) {
                if (!$this->_operators->unique(sprintf('name_%s', $language), $this->_form->getValue(sprintf('name_%s', $language)))) {
                    $conditionsMatched = $conditionsMatched && false;
                    $this->_form->setError(sprintf('name_%s', $language), $this->_lc->get('caption_for_name_should_be_unique'));
                }
            }
            if ($conditionsMatched) {
                return true;
            }
        }
        $this->_view = new Fire_View('management/operatoradd', true, MANAGEMENT_TEMPLATE_BODY);
        $this->_view->set('form', new Fire_Form_Helper($this->_form, $this->_lc));
        $this->_view->set('operators', $this->_operators->findAll(array(), array(), 1, ITEMS_UNLIMITED));
        $this->setCommons();
        return false;
    }
    
    function perform() {
        $result = $this->_operators->insert(
            array(
                'name_bg' => Fire_Sanitize::escapeStr($this->_form->getValue('name_bg')),
            	'name_en' => Fire_Sanitize::escapeStr($this->_form->getValue('name_en')),
            	'name_ru' => Fire_Sanitize::escapeStr($this->_form->getValue('name_ru'))
            )
        );
        
        if($result)
            $flash = 'caption_for_operator_added';
        else
            $flash = 'caption_for_insert_failed';
            
		$this->setFlash($flash);
        $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators'));
		return true;
    }
}
?>