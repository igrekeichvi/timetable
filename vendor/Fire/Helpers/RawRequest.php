<?php
/**
 *  Raw request generator class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Raw request generator class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_RawRequest_Helper extends Fire_Object {
    
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
        
        if (func_num_args() < 2) {
        	return './';
        }
        
        $_args = func_get_args();
        return 'index.php?' . str_replace('&', '&amp;', substr($this->query_string($_args), 1));
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
            	$query_string .= sprintf('&%s=%s', urlencode($args[$i]), $args[$i + 1]);
            }
        }
        return $query_string;
    }
}
?>