<?php
/**
 *  Date validator.
 *  @package    Library
 *  @subpackage Validators
 */

/** Loads validator interface. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Date validator.
 *  @package    Library
 *  @subpackage Validators
 */
class DateValidator extends Fire_Validator {
    
    /**
     * @var     string $format
     * @access  public 
     */
    var $format;
    
    /**
     * @var     string $part_separator
     * @access  public 
     */
    var $part_separator;
    
    /**
     * @var     boolean $future
     * @access  public 
     */
    var $future;
    
    /**
     * @var     boolean $past
     * @access  public 
     */
    var $past;
    
    /**
     *
     * Constructor
     * @param   string  $format
     * @param   string  $part_separator
     * @param   boolean $future
     * @param   boolean $part_separator
     * @access  public 
     *
     */
    function __construct($format = '%d.%m.%Y', $part_separator = '.', $future = true, $past = true) {
        parent::__construct();
        
        $this->format = $format;
        $this->part_separator = $part_separator;
        $this->future = $future;
        $this->past = $past;
    }
    
    /**
     *
     * Validates the given date value.
     * @param   string  $value
     * @return  boolean
     * @access  public 
     *
     */
    function validate($value) {
        
        if (empty($value)) {
        	$this->_error = 'error_message_for_date_is_empty';
        	return false;
        }
        
        $formated_date = $this->_format($value);
        
        if (!checkdate($formated_date['month'], $formated_date['day'], $formated_date['year'])) {
        	$this->_error = 'error_message_for_this_date_do_not_exists';
        	return false;
        }
        
        if (!$this->future && (@mktime(0, 0, 0, $formated_date['month'], $formated_date['day'], $formated_date['year']) > time())) {
        	$this->_error = 'error_message_for_date_is_in_the_future';
        	return false;
        }
        
        if (!$this->past && (@mktime(0, 0, 0, $formated_date['month'], $formated_date['day'], $formated_date['year']) < time())) {
        	$this->_error = 'error_message_for_date_is_in_the_past';
        	return false;
        }
        
        return true;
    }
    
    /**
     *
     * Formats the date according to format given to constructor arguments.
     * @param   string  $value
     * @return  mixed
     * @access  public 
     *
     */
    function _format($value) {
        
        $_format_parts = explode($this->part_separator, $this->format);
        $_date_parts = explode($this->part_separator, $value);
        
        $date_result = array();

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
        
        return $date_result;
    }
}
?>