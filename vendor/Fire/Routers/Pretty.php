<?php
/**
 * This file contains pretty URLs router class.
 * @package     Library
 * @subpackage  Routers
 */

/** Requires router class. */
require_once(FIRE_LIBRARY_PATH . 'Router.php');

/**
 * index.php/key/val/key/val
 * @package     Library
 * @subpackage  Routers
 */
class Fire_Routers_Pretty extends Fire_Router {
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        
        if (isset($_SERVER['PATH_INFO'])) {
        	$cfg =& Fire_Config::instance();
        	$_parts = explode('/', $_SERVER['PATH_INFO']);
        	$_parts = array_values(preg_grep('/^[a-zA-Z0-9:_\/-]+$/i', $_parts));
        	if (!empty($_parts)) {
        		$_GET[$cfg->get('router_action_parameter')] = $_parts[0];
            	if (count($_parts) > 1) {
            		for ($i = 1; $i < count($_parts); $i += 2) {
            		    if (isset($_parts[$i]) && isset($_parts[$i + 1])) {
            		    	$_GET[$_parts[$i]] = $_parts[$i + 1];
            		    }
                	}
            	}
        	}
        }
    }
    
    /**
     *
     * Reads the GET and store some of the variables.
     * @param   string  $route
     * @return  mixed
     * @access  public 
     *
     */
    function findRoute($route = '') {
        $route = $route ? $route : FIRE_DEFAULT_ROUTE;
        return $this->_routes->get($route);
    }
}
?>