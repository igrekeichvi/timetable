<?php
/**
 *  This file contains base view class.
 *  @package    Library
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Main View class.
 *  @package    Library
 */
class Fire_View extends Fire_Object {
    
    /**
     * @var     Smarty $_smarty
     * @access  private 
     */
    var $_smarty;
    
    /**
     * 
     * @var     string $_view_file
     * @access  private 
     */
    var $_view_file;
    
    /**
     * @var     string $_template_file_name
     * @access  private 
     */
    var $_template_file_name;
    
    /**
     * @var     string $_template_sub_directory
     * @access  private 
     */
    var $_template_sub_directory;
    
    /**
     * @var     string $content_type
     * @access  public 
     */
    var $content_type = 'text/html';
    
    /**
     * Response headers.
     * @var     mixed $_headers
     * @access  private 
     */
    var $_headers;
    
    /**
     *
     * Constructor.
     * @param   string  $view_file
     * @param   boolean $use_template_file
     * @param   string  $template_file_name
     * @access  public 
     *
     */
    function __construct($view_file, $use_template_file = false, $template_file_name = '') {
        parent::__construct();
        
        $this->_headers = array();
        
        $config =& Fire_Config::instance();
        $this->_view_file = $view_file . $config->get('default_template_extension');
        
        if (!Fire_Loader::loadClass('Smarty', 'Smarty.class', array($config->get('path_to_smarty_absolute')), true)) {
        	Fire_Error::throwError('Failed to load smarty.', __FILE__, __LINE__);
        }
        
        $this->_smarty = new Smarty;
        
        $this->_smarty->caching = false;
        $this->_smarty->php_handling = false;
        $this->_smarty->use_sub_dirs = false;
        
        $this->_smarty->security = $config->get('allow_php_code_in_templates');
        $this->_smarty->compile_dir  = $config->get('compiled_templates_dir');
        $this->_smarty->template_dir = sprintf('%s%s%s', FIRE_APPLICATION_PATH, DIRECTORY_SEPARATOR, 'views');
        $this->_smarty->compile_check = $config->get('template_compile_check');
        $this->_smarty->force_compile = $config->get('force_complie');
        
        if ($use_template_file) {
            $this->_template_file_name = ($template_file_name ? $template_file_name : $config->get('template_file_name')) . $config->get('default_template_extension');
        }
    }
    
    /**
     *
     * Sets a template fetch directory to be a subdirectory of the views.
     * @param   string  $views_sub_directory
     * @access  public 
     *
     */
    function directory($views_sub_directory) {
//        $this->_smarty->template_dir = $this->_smarty->template_dir . DIRECTORY_SEPARATOR . $views_sub_directory;
        $this->_template_sub_directory = $views_sub_directory . DIRECTORY_SEPARATOR;
//        $this->_smarty->compile_dir = $this->_smarty->compile_dir . DIRECTORY_SEPARATOR . $views_sub_directory;
    }
    
    /**
     *
     * Sets a var to the view as reference.
     * @param   mixed   $key
     * @param   mixed   $value
     * @access  public 
     *
     */
    function setAsRef($key, &$value) {
        $this->_smarty->assign_by_ref($key, $value);
    }
    
    /**
     *
     * Sets a var to the view.
     * @param   mixed   $key
     * @param   mixed   $value
     * @access  public 
     *
     */
    function set($key, $value) {
        $this->_smarty->assign($key, $value);
    }
    
    /**
     *
     * Adds a header.
     * @param   string  $header
     * @access  public 
     *
     */
    function addHeader($header) {
        array_push($this->_headers, $header);
    }
    
    /**
     *
     * Renders the view and displays everything.
     * @access  public 
     *
     */
    function render() {
        
        $this->setAsRef('meta_tags_content_type', $this->content_type);
        
        if (isset($this->_template_file_name)) {
        	$_contents = $this->_smarty->fetch($this->_template_sub_directory . $this->_view_file);
        	$this->_smarty->assign_by_ref('PAGE_CONTENTS', $_contents);
        	$this->_view_file = $this->_template_file_name;
        }
        
        return $this->_smarty->fetch($this->_template_sub_directory . $this->_view_file);
    }
}
?>