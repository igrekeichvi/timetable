<?php
/**
 *  This file containts input variables class.
 *  @package    Library
 */

/** Requires error class. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Requires filter class. */
require_once(FIRE_LIBRARY_PATH . 'Sanitize.php');

/**
 *  Input class.
 *  @package    Library
 */
class Fire_Input extends Fire_Object {
    
    /**
     *
     * Constructor.
     * @return  void
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        
        $this->_sanitizeInput($_GET);
        $this->_sanitizeInput($_POST);
        $this->_sanitizeInput($_COOKIE);
    }
    
    /**
     *
     * Singleton instance of input class.
     * @return  Fire_Input
     * @access  public 
     *
     */
    static function &instance() {
        static $instance;
        
        if (!isset($instance)) {
        	$instance = new Fire_Input();
        }
        
        return $instance;
    }
    
    /**
     *
     * Returns a value from the _GET array.
     * @param   string  $key
     * @param   boolean $xss
     * @return  string
     * @access  public 
     *
     */
    function get($key, $xss = false) {
        return $this->_get($_GET, $key, $xss);
    }
    
    /**
     *
     * Returns whole _GET array.
     * @param   boolean $xss_clean
     * @return  mixed
     * @access  public 
     *
     */
    function getGet($xss_clean = false) {
        return $this->_getArray($_GET, $xss_clean);
    }
    
    /**
     *
     * Returns a value from the _POST array.
     * @param   string  $key
     * @param   boolean $xss
     * @return  string
     * @access  public 
     *
     */
    function post($key, $xss = false) {
        return $this->_get($_POST, $key, $xss);
    }
    
    /**
     *
     * Returns whole _POST array.
     * @param   boolean $xss_clean
     * @return  mixed
     * @access  public 
     *
     */
    function getPost($xss_clean = false) {
        return $this->_getArray($_POST, $xss_clean);
    }
    
    /**
     *
     * Returns a value from the _COOKIE array.
     * @param   string  $key
     * @param   boolean $xss
     * @return  string
     * @access  public 
     *
     */
    function cookie($key, $xss = false) {
        return $this->_get($_COOKIE, $key, $xss);
    }
    
    /**
     *
     * Returns whole _COOKIE array.
     * @param   boolean $xss_clean
     * @return  mixed
     * @access  public 
     *
     */
    function getCookie($xss_clean = false) {
        return $this->_getArray($_COOKIE, $xss_clean);
    }
    
    /**
     *
     * Returns a value from given input array.
     * @param   mixed   $array
     * @param   string  $key
     * @param   boolean $xss
     * @return  string
     * @access  public 
     *
     */
    function _get($array, $key, $xss = false) {
        
        if (!isset($array[$key])) {
            return null;
        }
        
        if (!$xss) {
        	return $array[$key];
        }
        
        if (is_array($array[$key])) {
            
        	foreach ($array[$key] as $_k => $_v) {
        		$array[$key][$_k] = Fire_Sanitize::XSSClean($_v);
        	}
        	
        	return $array[$key];
        	
        } else {
            return Fire_Sanitize::XSSClean($array[$key]);
        }
    }
    
    /**
     *
     * Returns whole input array as array.:P
     * @param   mixed   $array
     * @return  mixed
     * @access  public 
     *
     */
    function _getArray($array, $xss_clean= false) {
        if (!$xss_clean) {
        	return $array;
        }
        
        foreach ($array as $_array_key => $_array_value) {
        	$array[$_array_key] = $this->_get($array, $_array_key, $xss_clean);
        }
        
        return $array;
    }
    
    /**
     *
     * Cleans the request array.
     * @param   mixed   $array
     * @access  public 
     *
     */
    function _sanitizeInput(&$array) {
        foreach ($array as $input_key => $input_value) {
            $array[$this->_sanitizeKey($input_key)] = $this->_sanitizeData($input_value);
        }
    }
    
    /**
     *
     * Cleans the input key.
     * @param   string  $key
     * @return  string
     * @access  public 
     *
     */
    function _sanitizeKey($key) {
        
        if (!strlen($key)) {
        	return;
        }
        
        if (!preg_match('/^[a-zA-Z0-9:_\/-]+$/i', $key)) {
        	Fire_Error::throwError('Invalid characters used in input key.', __FILE__, __LINE__, true);
        }
        
        return urldecode(preg_replace('/\_\_(.+?)\_\_/', '', $key));
    }
    
    /**
     *
     * Cleans input data field.
     * @param   string  $data
     * @return  string
     * @access  private 
     *
     */
    function _sanitizeData($data) {
        
        if (is_array($data)) {
        	$this->_sanitizeInput($data);
        	return $data;
        }
        
        if (!get_magic_quotes_gpc()) {
        	$data = addslashes($data);
        }
        
        return Fire_Sanitize::normalize($data, false);
    }
}
?>