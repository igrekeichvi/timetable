<?php
Fire_Loader::loadClass('ManagementDefaultAction', 'Default', array(FIRE_APPLICATION_PATH, 'actions', 'management'), true);
define('OPERATORS_MODEL_NAME', 'operators');
class ManagementOperatorDeleteAction extends ManagementDefaultAction {
    
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
            $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators', '_message_flash', 'caption_for_operator_not_found'));
            return false;
        }
        return true;
    }
    
    function perform() {
        $result = $this->_operators->delete(
            array(sprintf('id = %d', Fire_Sanitize::escapeStr($this->_operator_id)))
        );
        
        if ($result)
            $flash = 'caption_for_operator_deleted';
        else
            $flash = 'caption_operator_not_deleted';
        $this->setRedirectRoute($this->_rq->href($this->_config->get('router_action_parameter'), 'm_operators', '_message_flash', $flash));
    }
}
?>