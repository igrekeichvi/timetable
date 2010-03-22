<?php
/**
 *  Locale class.
 *  @package    Library
 */

/** Loads the error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Loads the config. */
require_once(FIRE_LIBRARY_PATH . 'Config.php');
/** Loads the sanitize. */
require_once(FIRE_LIBRARY_PATH . 'Sanitize.php');

/**
 *  Locale class.
 *  @package    Library
 */
class Fire_Locale extends Fire_Object {
    
    /**
     * @var     string $_lang
     * @access  private 
     */
    var $_lang;
    
    /**
     * @var     mixed $_messages
     * @access  private 
     */
    var $_messages = array();
    
    /**
     *
     * Constrcuctor. Toss the shorthand languages (en, bg, ru etc).
     * @param   string  $lang
     * @access  public 
     *
     */
    function __construct($language = '') {
        parent::__construct();
        
        $config =& Fire_Config::instance();
        
        if (!strlen($language) || !in_array($language, $config->get('languages'))) {
        	$this->_lang = $config->get('default_lang');
        } else {
            $this->_lang = $language;
        }
        
        if (!$this->_lang) {
        	Fire_Error::throwError('Locale has nothing to load.', __FILE__, __LINE__);
        }
        
        $this->load('base');
    }
    
    /**
     *
     * Singleton instance.
     * @param   string  $lang
     * @return  Fire_Locale
     * @access  public 
     *
     */
    static function &instance($lang = '') {
        static $instance;
        
        if (!isset($instance)) {
        	$instance = new Fire_Locale($lang);
        }
        
        return $instance;
    }
    
    /**
     *
     * Returns current language.
     * @return  string
     * @access  public 
     *
     */
    function getLang() {
        return $this->_lang;
    }
    
    /**
     *
     * Load given language definition file. The array with language definitions
     * must <strong>_be_</strong> with the same name as the file name.
     * @param   string  $file
     * @param   boolean $silent
     * @access  public 
     *
     */
    function load($file, $silent = true) {
        $_path = sprintf('%1$s%2$slanguage%2$s%3$s%2$s%4$s.php', FIRE_APPLICATION_PATH, DIRECTORY_SEPARATOR, $this->_lang, $file);
        if ((!file_exists($_path) || !is_readable($_path)) && !$silent) {
        	Fire_Error::throwError(sprintf('Failed to load language file: "%s"', $_path), __FILE__, __LINE__);
        }
        
        include($_path);
        
        if (isset($$file) && is_array($$file)) {
        	$this->_messages = array_merge($this->_messages, $$file);
        	unset($$file);
        }
    }
    
    /**
     *
     * Returns a line with corresponding caption from the locale table.
     * @param   string  $caption
     * @param   string  $text
     * @param   boolean $sanitize
     * @param   boolean $convert_new_lines
     * @param   boolean $xss_clean
     * @return  string
     * @access  public 
     *
     */
    function get($caption, $text = '', $sanitize = true, $convert_new_lines = true, $xss_clean = false) {
        if (!array_key_exists($caption, $this->_messages)) {
        	$line = $caption;
        	return $line;
        } else {
            $line = $this->_messages[$caption];
        }
        
        if (strpos($line, '%s') !== false) {
        	$line = sprintf($line, $text);
        }
        
        if ($sanitize) {
        	$line = Fire_Sanitize::normalize($line, $convert_new_lines, $xss_clean);
        }
        
        return $line;
    }
    
    /**
     *
     * Sanitizes a given string. It's shortcut to Sanitize class.
     * @param   string  $string
     * @param   boolean $convert_new_lines
     * @param   boolean $xss_clean
     * @return  string
     * @access  public 
     *
     */
    function san($string, $convert_new_lines = true, $xss_clean = false) {
        return Fire_Sanitize::normalize($string, $convert_new_lines, $xss_clean);
    }
    
    /**
     *
     * Strips.
     * @param   string  $string
     * @return  string
     * @access  public 
     *
     */
    function strip($string) {
        return Fire_Sanitize::filterStr($string, true);
    }
    
    /**
     *
     * Convert a data, with given format and separator, to SQL format.
     * @param   string  $date
     * @return  string
     * @access  public 
     *
     */
    function standartize($date) {
        
        $_format_parts = explode($this->_messages['base_date_format_separator'], $this->_messages['base_date_format']);
        $_date_parts = explode($this->_messages['base_date_format_separator'], $date);
        while (list($_format_part_key, $_format_part_value) = each($_format_parts)) {
        	switch (strtolower($_format_part_value)) {
        		case '%d':
        			$date_result['day'] = isset($_date_parts[$_format_part_key]) ? intval($_date_parts[$_format_part_key]) : -1;
        			break;
        		case '%m':
        		    $date_result['month'] = isset($_date_parts[$_format_part_key]) ? intval($_date_parts[$_format_part_key]) : -1;
        			break;
    			case '%y':
        		    $date_result['year'] = isset($_date_parts[$_format_part_key]) ? intval($_date_parts[$_format_part_key]) : -1;
        			break;
    			default:
    			    Fire_Error::throwError('Wtf?', __FILE__, __LINE__);
    			    break;
        	}
        }
        
        return sprintf('%d:%d:%d 12:00:00', $date_result['year'], $date_result['month'], $date_result['day']);
    }
    
    /**
     *
     * Convert a data, with given format and separator, to SQL format.
     * @param   string  $date
     * @return  string
     * @access  public 
     *
     */
    function localize($date) {
        
        if (!strlen($date)) {
        	return '';
        }
        
        $_date_parts = explode('-', substr($date, 0, strpos($date, ' ')));
        
        $a = preg_replace(
            array('/\%d/', '/\%m/', '/\%Y/'),
            array($_date_parts[2], $_date_parts[1], $_date_parts[0]),
            $this->_messages['base_date_format']
        );
        
        return $a;
    }
}
?>