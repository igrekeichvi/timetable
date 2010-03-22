<?php
/**
 * This files contains Object class.
 * @package     Library
 */

/** Defining library path, if it's not already defined. */
if (!defined('FIRE_LIBRARY_PATH')) {
	define(FIRE_LIBRARY_PATH, dirname(__FILE__));
}

/**
 * Object class.
 * @package     Library
 */
class Fire_Object {
    
    /**
     * Constructor.
     * @return void
     * @access public 
     *
     */
    function Fire_Object() {
        
		if (method_exists($this, '__destruct')) {
			register_shutdown_function (array(&$this, '__destruct'));
		}
		
		$args = func_get_args();
		call_user_func_array(array(&$this, '__construct'), $args);
    }
    
    /**
     *
     * A PHP 5 constructor.
     * @return  void
     * @access  public 
     *
     */
    function __construct() {}

    /**
     * Returns object class name.
     * @return string
     * @access public 
     *
     */
    function getName() {
        return get_class($this);
    }
    
    /**
     * Returns parent class name.
     * @return string
     * @access public 
     *
     */
    function getParent() {
        return get_parent_class($this);
    }
    
    /**
     *
     * Check if this object has method.
     * @param   string  $name   Method name.
     * @return  boolean
     * @access  public 
     *
     */
    function hasMethod($name) {
        return method_exists($this, $name);
    }
    
    /**
     *
     * Dumps object variables as string.
     * 
     * @return string
     * @access public 
     *
     */
    function toString() {
        return var_export($this);
    }
}
?>