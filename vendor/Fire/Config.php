<?php
/**
 * This file contains configuration reader and write class.
 * @package     Library
 */

/** Define default config file. */
if (!defined('FIRE_DEFAULT_CONFIGURATION_FILE')) {
	define('FIRE_DEFAULT_CONFIGURATION_FILE', sprintf('%1$s%2$s%3$s%2$s%4$s', FIRE_APPLICATION_PATH, DIRECTORY_SEPARATOR, 'config', 'config.php'));
}

/**
 * Configuration class. Can read and write configuration directives.
 * @package     Library
 */
class Fire_Config extends Fire_Object {
    
    /**
     * @var     mixed $_directives
     * @access  private
     */
    var $_directives = array();
    
    /**
     *
     * Constructor.
     * @param   string  $configuration_file
     * @access  public 
     *
     */
    function __construct($configuration_file = FIRE_DEFAULT_CONFIGURATION_FILE) {
        parent::__construct();
        if (file_exists($configuration_file) && is_readable($configuration_file)) {
        	require($configuration_file);
        	$this->_directives = ${strtolower(str_replace('.php', '', basename($configuration_file)))};
        } else {
            Fire_Error::throwError('Configuration file can not be readed.', __FILE__, __LINE__);
        }
    }
    
    /**
     *
     * Singleton instance of config class.
     * @param   string  $configuration_file
     * @return  Fire_Config
     * @access  public 
     *
     */
    static function &instance($configuration_file = FIRE_DEFAULT_CONFIGURATION_FILE) {
        static $instance;
        
        if (!isset($instance)) {
        	$instance = new Fire_Config($configuration_file);
        }
        
        return $instance;
    }
    
    /**
     *
     * Returns a configuration directorives.
     * @param   mixed   $key
     * @return  mixed   
     * @access  public 
     *
     */
    function get($key) {
        return isset($this->_directives[$key]) ? $this->_directives[$key] : null;
    }
}
?>