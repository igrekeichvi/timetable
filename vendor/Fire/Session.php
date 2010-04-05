<?php
/**
 *  Session class.
 *  @package    Library
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Session class.
 *  @package    Library
 */
class Fire_Session extends Fire_Object {
    
	private $vars = array();
	
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
     * Returns singleton instance of the session.
     * @param   string  $session_name
     * @return  Fire_Session
     * @access  public 
     *
     */
    static function &instance() {
        static $instance;
        
        if (!isset($instance)) {
            $instance = new Fire_Session();
            foreach ($_SESSION as $key => $value) {
            	$instance->$key = $value;
            }
        }
        return $instance;
    }
    
    /**
     *
     * Starts the session.
     * @param   string  $session_name
     * @param   integer $session_lifetime
     * @param   boolean $secure
     * @access  public
     * @static 
     */
    static function init($session_name = 'default_session', $session_lifetime = 3600, $secure = false) {
        
    	if (session_id()) {
        	Fire_Error::throwError('Session is started already', __FILE__, __LINE__);
        }
		
		session_start();
            
        session_name($session_name);
//        $__cookie_path = substr($_SERVER['SCRIPT_NAME'], 0, strlen($_SERVER['SCRIPT_NAME']) - (strrpos($_SERVER['SCRIPT_NAME'], '/') + 1));
        $__cookie_path = substr($_SERVER['SCRIPT_NAME'], 0, (strrpos($_SERVER['SCRIPT_NAME'], '/') + 1));

        session_set_cookie_params(
            $session_lifetime, 
            ((strlen($__cookie_path) == 0) || ($__cookie_path == '\\')) ? '/' : $__cookie_path,
            isset($_SERVER['HTTP_X_FORWARDED_SERVER']) ? $_SERVER['HTTP_X_FORWARDED_SERVER'] : $_SERVER['HTTP_HOST'],
            $secure
		);
    }
    
    /**
     *
     * Writes down and closes session.
     * @access  public 
     *
     */
    function write() {
		$_SESSION = array();
		while (list($key, $value) = each($this->vars)) {
        	$_SESSION[$key] = $value;
        }
        //session_write_close();
    }
    
    /**
     *
     * Destroys the session.
     * @access  public 
     *
     */
    function destroy() {
        session_destroy();
        unset($this);
    }
	
	function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	function __get($name) {
		if (array_key_exists($name, $this->vars))
			return $this->vars[$name];
		
		return null;
	}
	
	function set($name, $value) {
		$this->vars[$name] = $value;
	}
	
	function present($name) {
		return array_key_exists($name, $this->vars);
	}
	
	function get($name) {
		if ($this->present($name)) {
			return $this->vars[$name];
		}
		return null;
	}
	
	function remove($name) {
		if ($this->present($name)) {
			unset($this->vars[$name]);
		}
	}
}
?>