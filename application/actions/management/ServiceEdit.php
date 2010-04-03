<?php
Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);

class ManagementServiceEditAction extends ManagementDefaultAction {
    
    private $_servicesModel;
    private $_operatorsModel;
    
    function __construct() {
        parent::__construct();
        $this->_servicesModel = Fire_Loader::model('services', true, true);
        $this->_operatorsModel = Fire_Loader::model('operators', true, true);
    }
    
    function validate() {
        
        $operators = $this->_operatorsModel->generateList('id', 'name_bg');
        
        if (empty($operators)) {
            $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators', '_message_flash', 'caption_for_add_operators_first'));
        }
        
        $service = $this->_servicesModel->find(array(sprintf('id = %d', $this->_input->get('id', true))));
        if (empty($service))
            $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_services', '_message_flash', 'caption_for_service_not_found'));
        
        // map operator_id to operator
        $service[0]['operator'] = $service[0]['operator_id'];
            
        $this->_form = new Fire_Form($service[0]);
        $this->_form->register('name_bg', new StringValidator(1, 50));
        $this->_form->register('name_en', new StringValidator(1, 50));
        $this->_form->register('name_ru', new StringValidator(1, 50));
        $this->_form->register('code', new StringValidator(1, 10), true);
        $this->_form->register('operator', new EnumValidator(array_keys($operators)));
        
        if ($this->_input->hasPost()) {
	        $conditionsMatched = true;
	        if (!$this->_form->validate($this->_input->getPost(true))) {
	            $conditionsMatched = $conditionsMatched && false;  
	        }
	        
	        $serviceByName = $this->_servicesModel->find(array(sprintf('name_bg = %s', Fire_Sanitize::qstr($this->_form->getValue('name_bg')))));
	        if (!empty($serviceByName) && ($serviceByName[0]['name_bg'] != $this->_form->getValue('name_bg'))) {
	            $this->_form->setError('name_bg', $this->_lc->get('caption_for_name_should_be_unique'));
	            $conditionsMatched = $conditionsMatched && false;
	        }
	            
	        $serviceByName = $this->_servicesModel->find(array(sprintf('name_en = %s', Fire_Sanitize::qstr($this->_form->getValue('name_en')))));
	        if (!empty($serviceByName) && ($serviceByName[0]['name_en'] != $this->_form->getValue('name_en'))) {
	            $this->_form->setError('name_en', $this->_lc->get('caption_for_name_should_be_unique'));
	            $conditionsMatched = $conditionsMatched && false;
	        }
	            
	        $serviceByName = $this->_servicesModel->find(array(sprintf('name_ru = %s', Fire_Sanitize::qstr($this->_form->getValue('name_ru')))));
	        if (!empty($serviceByName) && ($serviceByName[0]['name_ru'] != $this->_form->getValue('name_ru'))) {
	            $this->_form->setError('name_ru', $this->_lc->get('caption_for_name_should_be_unique'));
	            $conditionsMatched = $conditionsMatched && false;
	        }
	        
	        $serviceByCode = $this->_servicesModel->find(array(sprintf('code = %s', Fire_Sanitize::qstr($this->_form->getValue('code')))));
	        if (!empty($serviceByCode) && ($serviceByCode[0]['code'] != $this->_form->getValue('code'))) {
	            $this->_form->setError('code', $this->_lc->get('caption_for_code_should_be_unique'));
	            $conditionsMatched = $conditionsMatched && false;
	        }
	            
	        if (!$this->_operatorsModel->find(sprintf('id = %d', $this->_form->getValue('operator')))) {
	            $this->_form->setError('operator', $this->_lc->get('caption_for_select_operator'));
	            $conditionsMatched = $conditionsMatched && false;
	        }
	            
	        if ($conditionsMatched == true)
	            return true;
        }
        
        $this->_view = new Fire_View('management/services', true, MANAGEMENT_TEMPLATE_BODY);
        $this->_view->set('form', new Fire_Form_Helper($this->_form, $this->_lc));
        $this->_view->set('services', $this->_servicesModel->getServices(1, ITEMS_UNLIMITED));
        $this->_view->set('operators', $operators);
        $this->_view->set('service', $service[0]);
        $this->setCommons();
        
        return false;
    }
    
    function perform() {
        
        $code = Fire_Sanitize::escapeStr($this->_form->getValue('code'));
        
        $result = $this->_servicesModel->update(
            array(
                'operator_id' => Fire_Sanitize::escapeStr($this->_form->getValue('operator')),
                'code'		  => empty($code) ? null : $code,
                'name_bg'     => Fire_Sanitize::escapeStr($this->_form->getValue('name_bg')),
            	'name_ru'     => Fire_Sanitize::escapeStr($this->_form->getValue('name_en')),
                'name_en'     => Fire_Sanitize::escapeStr($this->_form->getValue('name_ru'))
            ),
            array(sprintf('id = %d', Fire_Sanitize::escapeStr($this->_input->get('id', true))))
        );
        
        if ($result)
            $flash = 'caption_for_service_updated';
        else
            $flash = 'caption_for_service_update_failed';    
        
        $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_services', '_message_flash', $flash));
    }
}
?>