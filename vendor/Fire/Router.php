<?php
/**
 * This file contains interface for routers classes.
 * @package     Library
 */

/** Requires error class. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Requires input class. */
require_once(FIRE_LIBRARY_PATH . 'Input.php');
/** Requires input class. */
require_once(FIRE_LIBRARY_PATH . 'Config.php');

/** Defining default routes fire. */
if (!defined('FIRE_ROUTES_FILE')) {
	define('FIRE_ROUTES_FILE', sprintf('%1$s%2$s%3$s%2$s%4$s', FIRE_APPLICATION_PATH, DIRECTORY_SEPARATOR, 'config', 'routes.php'));
}

/**
 * Factory for routers classes.
 * @package     Library
 */
class Fire_Router extends Fire_Object {
    
    /**
     * Routes.
     * @var     Fire_Config $_routes
     * @access  private 
     */
    var $_routes;
    
    /**
     *
     * Constructor.
     * @param   string  $routes_file
     * @access  public 
     *
     */
    function __construct($routes_file = '') {
        parent::__construct();
        
        if (!strlen($routes_file)) {
        	$routes_file = FIRE_ROUTES_FILE;
        }
        
        $this->_routes = new Fire_Config($routes_file);
    }
    
    /**
     *
     * Reads the GET and store some of the variables.
     * @param   string
     * @access  public 
     *
     */
    function findRoute($route = '') {
        Fire_Error::throwError('Fire_Router::findRoute() should be implemented by child classes.', __FILE__, __LINE__);
    }
}
?>