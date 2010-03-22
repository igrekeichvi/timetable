<?php
/**
 *  This is front controller class.
 *  @package    Library
 */

/** Defining default action name. */
if (!defined('FIRE_DEFAULT_ROUTE')) {
	define('FIRE_DEFAULT_ROUTE','default');
}

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');
/** Loads error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Loads loader. */
require_once(FIRE_LIBRARY_PATH . 'Loader.php');
/** Loads response. */
require_once(FIRE_LIBRARY_PATH . 'Response.php');
/** Loads action. */
require_once(FIRE_LIBRARY_PATH . 'Action.php');
/** Loads view. */
require_once(FIRE_LIBRARY_PATH . 'View.php');
/** Loads model. */
require_once(FIRE_LIBRARY_PATH . 'Model.php');

/**
 *  Front Controller class.
 *  @package    Library
 */
class Fire_Front_Controller extends Fire_Object {
    
    /**
     * Composite view, collected from the execution of the actions.
     * @var     Fire_Response $_response
     * @access  private 
     */
    var $_response;
    
    /**
     *
     * Constructor
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        $this->_response = new Fire_Response();
    }
    
    /**
     *
     * Process the application flow.
     * @param   Fire_Router $router
     * @access  public 
     * @static 
     *
     */
    static function process(&$router) {
        static $controller;

        if (!isset($controller)) {
            $controller = new Fire_Front_Controller();
        }
        
        $controller->run($router);
    }
    
    /**
     *
     * Runs the process.
     * @param   Fire_Router $router
     * @access  public
     *
     */
    function run(&$router) {
        
        $input =& Fire_Input::instance();
        $config =& Fire_Config::instance();
        
        $_route_result = $router->findRoute($input->get($config->get('router_action_parameter')));

        if (is_null($_route_result)) {
        	Fire_Error::throwError('Implement 404 page right here!');
        }
        
        while ($_route_result !== null) {
            
            $_action_class_name = $_route_result['class'] . 'Action';
            $_action_file_name = $_route_result['file'];
            
        	$this->_loadAction($_action_file_name, $_action_class_name);
        	$action_object = new $_action_class_name();
        	/* @var action_object Action */
        	
        	
        	if ($action_object->permit()) {
    	        if ($action_object->validate()) {
    	        	$action_object->perform();
    	        }
        	}
        	
        	if (isset($action_object->redirect_route)) {
        		$this->_response->redirect($action_object->redirect_route);
        		exit(1);
        	}
        	
        	if (isset($action_object->forward_route)) {
        		$_route_result = $router->findRoute($action_object->forward_route);
        	} else {
        	    $_route_result = null;
        	}
        	
        	if (isset($action_object->_view)) {
        	    
        	    while (list(, $view_header) = each($action_object->_view->_headers)) {
        	    	$this->_response->addHeader($view_header);
        	    }
        	    
        		$this->_response->setResponseBody($action_object->getView());
        	}
        }
        
        $this->_response->sendHeaders();
        $this->_response->sendContents();
    }
    
    /**
     *
     * Loads the action file.
     * @param   string  $action_file
     * @param   string  $action_class
     * @access  public 
     *
     */
    function _loadAction($action_file, $action_class) {
        if (!Fire_Loader::loadClass($action_class, $action_file, array(FIRE_APPLICATION_PATH, 'actions'), true)) {
        	Fire_Error::throwError(sprintf('Request action class "%s" was not found in file "%s". Either file can\'t be found or class in not present there.', $action_class, $action_file), __FILE__, __LINE__);
        }
    }
}
?>