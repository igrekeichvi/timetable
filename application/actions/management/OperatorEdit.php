<?php
Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);
define('OPERATORS_MODEL_NAME', 'operators');
class ManagementOperatorEditAction extends ManagementDefaultAction {
    
    private $_operator_id;
    private $_operators;
        
    function __construct() {
        parent::__construct();
        $this->_operator_id = $this->_input->get('id', true);
        $this->_operators = Fire_Loader::model(OPERATORS_MODEL_NAME, true, true);
    }
    
    function validate() {
        $condition = sprintf('id = %d', $this->_operator_id);
        if (!$operator = $this->_operators->find(array($condition))) {
			$this->setFlash('caption_for_operator_not_found');
            $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators'));
            return false;
        }
        
        $this->_form = new Fire_Form(array('name_bg' => $operator[0]['name_bg'], 'name_en' => $operator[0]['name_en'], 'name_ru' => $operator[0]['name_ru']));
        $languages = $this->_config->get('languages');
        while(list(,$language) = each($languages)) {
            $this->_form->register(sprintf('name_%s', $language), new StringValidator(1, 50));
        }
        
        if ($this->_form->validate($this->_input->getPost(true))) {
            
            $conditionsMatched = true;
            while(list(,$language) = each($languages)) {
                $field = sprintf('name_%s', $language);
                $existingOperatorByName = $this->_operators->find(array(sprintf('%s = %s', $field, Fire_Sanitize::qstr($this->_form->getValue($field)))));
                if (!empty($existingOperatorByName) && ($existingOperatorByName[0][$field] != $this->_form->getValue($field))) {
                    $conditionsMatched = $conditionsMatched && false;
                    $this->_form->setError($field, $this->_lc->get('caption_for_name_should_be_unique'));
                }
            }
            if ($conditionsMatched) {
                return true;
            }
        }
        $this->_view = new Fire_View('management/operatoredit', true, MANAGEMENT_TEMPLATE_BODY);
        $this->_view->set('form', new Fire_Form_Helper($this->_form, $this->_lc));
        $this->_view->set('operator', $operator[0]); 
        $this->setCommons();
        return false;
    }
    
    function perform() {
        $resut = $this->_operators->update(
            array(
                'name_bg' => Fire_Sanitize::escapeStr($this->_form->getValue('name_bg')),
            	'name_en' => Fire_Sanitize::escapeStr($this->_form->getValue('name_en')),
            	'name_ru' => Fire_Sanitize::escapeStr($this->_form->getValue('name_ru'))
            ),
            array(sprintf('id = %d', Fire_Sanitize::escapeStr($this->_operator_id)))
        );
		
		if ($result)
			$this->setFlash('caption_for_update_sucessfull');
		else
			$this->setFlash('caption_for_update_failed');
        
        $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators'));
    }
}
?>