<?php
/**
 *  Pager class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Pager class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Pager_Helper extends Fire_Object {
    
    /**
     * @var     string $list_action
     * @access  public 
     */
    var $list_action;
    
    /**
     * @var     integer $current
     * @access  public
     */
    var $current;
    
    /**
     * 
     * @var     integer $last
     * @access  public 
     */
    var $last;
    
    /**
     * @var     integer $rows_per_page
     * @access  public 
     */
    var $rows_per_page;
    
    /**
     * @var     integer $links_per_page
     * @access  public 
     */
    var $links_per_page;
    
    /**
     *
     * Constructor.
     * @param   string  $list_action
     * @param   mixed   $pager
     * @param   integer $links_per_page
     * @access  public 
     *
     */
    function __construct($list_action, $pager, $links_per_page = 5) {
        parent::__construct();
        
        if (empty($pager)) {
        	return;
        }
        
        $this->list_action = $list_action;
        $this->current = $pager['current'] > $pager['last'] ? $pager['last'] : ($pager['current'] < 1 ? 1 : $pager['current']);
        $this->last = $pager['last'];
        $this->rows_per_page = $pager['rows'];
        $this->links_per_page = $links_per_page;
    }
    
    /**
     *
     * Returns pages to display.
     * @return  integer
     * @access  public 
     *
     */
    function getPages() {
        
        static $_start;
        if (!isset($_start)) {
            $_start = $this->current - floor($this->links_per_page / 2);
            $_start = $_start < 1 ? 1 : $_start;
        }
        
        static $_end;
        if (!isset($_end)) {
            $_end = $this->current + floor($this->links_per_page / 2);
            $_end = $_end <= $this->last ? $_end : $this->last;
        }
        
        static $i;
        if (!isset($i)) {
        	$i = $_start - 1;
        }
        
        while($i < $_end) {
            $i++;
            return $i;
        }
    }
    
    /**
     *
     * Returns pages as array.
     * @return  mixed
     * @access  public 
     *
     */
    function toArray() {
        $_pages = array();
        while ($_page = $this->getPages()) {
        	array_push($_pages, $_page);
        }
        return $_pages;
    }
}
?>