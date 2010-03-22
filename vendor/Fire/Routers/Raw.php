<?php
/**
 * This file contains raw implementation of the router class..
 * @package     Library
 * @subpackage  Routers
 */

/** Requires router class. */
require_once(FIRE_LIBRARY_PATH . 'Router.php');

/**
 * Implementation of raw router, ie variables from type of key1=value1&key2=value2.
 * @package     Library
 * @subpackage  Routers
 */
class Fire_Routers_Raw extends Fire_Router {
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
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