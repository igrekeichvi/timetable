<?php
/**
 *  Pretty request generator class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Pretty request generator class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_PrettyRequest_Helper extends Fire_Object {
    
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
     * Generates a link href string.
     * @param   mixed
     * @return  string
     * @access  public 
     *
     */
    function href() {
        
        $query_string = 'index.php/';
        if (func_num_args() > 0) {
        
            $args = func_get_args();
            $query_string .= $args[0].'/';
            for ($i = 1; $i < count($args); $i += 2) {
                if (isset($args[$i]) && isset($args[$i + 1])) {
                	$query_string .= sprintf('%s/%s/', urlencode($args[$i]), $args[$i + 1]);
                }
            }
        
        }
        
        if (class_exists('Fire_Locale')) {
        	$lc =& Fire_Locale::instance();
        	$query_string .= '?lang='.$lc->getLang();
        }
        
        return $query_string;
    }
    
    /**
     *
     * Returns query string.
     * @param   mixed   $args
     * @return  string
     * @access  public 
     *
     */
    function query_string($args) {
        $query_string = '';
        for ($i = 0; $i < count($args); $i += 2) {
            if (isset($args[$i]) && isset($args[$i + 1])) {
            	$query_string .= sprintf('&amp;%s=%s', urlencode($args[$i]), $args[$i + 1]);
            }
        }
        return $query_string;
    }
    
    /**
     *
     * Generates a segment.
     * @param   string  $segment
     * @return  string
     * @access  public 
     *
     */
    function segment($segment) {
        return str_replace('&', '&amp;', sprintf('%s%s#%s',
            isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '',
            strlen($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '',
            $segment
        ));
    }
    
    /**
     *
     * Generates a language change link.
     * @param   string  $lang
     * @return  string
     * @access  public 
     *
     */
    function lang($lang) {
        
        if (strlen($_SERVER['QUERY_STRING']) && (strpos('lang=', $_SERVER['QUERY_STRING']) !== false)) {
        	$_query_string = preg_replace('/(lang=){1}(\w)+/', '\\1'.$lang, $_SERVER['QUERY_STRING']);
        } else {
            $_query_string = 'lang='.$lang;
        }
        
        return str_replace('&', '&amp;', sprintf('%s?%s',
            isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '',
            $_query_string
        ));
    }
}
?>