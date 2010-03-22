<?php
/**
 *  Form class.
 *  @package    Library
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');
/** Get sanitizer. */
require_once(FIRE_LIBRARY_PATH . 'Sanitize.php');
/** Loads validator validator. */
require_once(sprintf('%1$s%2$sValidators%2$sValidator.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads boolean validator. */
require_once(sprintf('%1$s%2$sValidators%2$sBoolean.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads date validator. */
require_once(sprintf('%1$s%2$sValidators%2$sDate.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads double validator. */
require_once(sprintf('%1$s%2$sValidators%2$sDouble.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads email validator. */
require_once(sprintf('%1$s%2$sValidators%2$sEmail.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads enum validator. */
require_once(sprintf('%1$s%2$sValidators%2$sEnum.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads file validator. */
require_once(sprintf('%1$s%2$sValidators%2$sFile.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads integer validator. */
require_once(sprintf('%1$s%2$sValidators%2$sInteger.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads ip address validator. */
require_once(sprintf('%1$s%2$sValidators%2$sIp.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads phone validator. */
require_once(sprintf('%1$s%2$sValidators%2$sPhone.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads string validator. */
require_once(sprintf('%1$s%2$sValidators%2$sString.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads text validator. */
require_once(sprintf('%1$s%2$sValidators%2$sText.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));
/** Loads url validator. */
require_once(sprintf('%1$s%2$sValidators%2$sUrl.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Form class.
 *  @package    Library
 */
class Fire_Form extends Fire_Object {
    
    /**
     * @var     mixed $_fields_validators
     * @access  private 
     */
    var $_fields_validators;
    
    /**
     * @var     mixed $_fields_values
     * @access  private 
     */
    var $_fields_values;
    
    /**
     * desc
     * @var     mixed $_fields_errors
     * @access  private 
     */
    var $_fields_errors;
    
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct($fields_values = array()) {
        parent::__construct();
        
        $this->_fields_errors = array();
        $this->_fields_values = $fields_values;
        $this->_fields_validators = array();
    }
    
    /**
     *
     * Registers fields to be validated.
     * @param   string          $field_name
     * @param   Fire_Validator  $validator_class
     * @param   boolean         $optional
     * @access  public 
     *
     */
    function register($field_name, $validator_class, $optional = false) {
        $this->_fields_validators[$field_name] = array('validator' => $validator_class, 'optional' => $optional);
    }
    
    /**
     *
     * Validated the request.
     * @param   mixed   $post_data
     * @return  mixed
     * @access  public 
     *
     */
    function validate($post_data = array()) {
        
        if (empty($this->_fields_validators)) {
        	return true;
        }
        
        if (empty($post_data)) {
        	return false;
        }
        
        $_is_form_valid = true;
        
        foreach ($this->_fields_validators as $_field_name => $_validator) {
        	/* @var _validator['validator'] Fire_Validator */
        	if (empty($post_data[$_field_name])) {
        	    if ($_validator['optional']) {
        	        $this->_fields_values[$_field_name] = '';
        	    	continue;
        	    } else {
        	        if (strpos(strtolower($_validator['validator']->getName()), 'file') !== false) {
        	        	$post_data[$_field_name] = $_field_name;
        	        } else {
        	            $post_data[$_field_name] = '';
        	        }
        	    }
        	}
        	
        	if (is_array($post_data[$_field_name])) {
        		foreach ($post_data[$_field_name] as $_post_data_field_key => $_post_data_field_value) {
        			$post_data[$_field_name][$_post_data_field_key] = Fire_Sanitize::reverse($_post_data_field_value);
        		}
        	} else {
        	    $post_data[$_field_name] = Fire_Sanitize::reverse($post_data[$_field_name]);
        	}
        	
        	if (!$_validator['validator']->validate($post_data[$_field_name])) {
        	    $_is_form_valid = $_is_form_valid && false;
        	    $this->_fields_errors[$_field_name] = $_validator['validator']->getError();
        	} else {
        	    $_is_form_valid = $_is_form_valid && true;
        	}
        	
        	$this->_fields_values[$_field_name] = $post_data[$_field_name];
        }
        
        return $_is_form_valid;
    }
    
    /**
     *
     * Sets field error message.
     * @param   string  $field_name
     * @param   string  $message
     * @access  public 
     *
     */
    function setError($field_name, $message) {
        if (isset($this->_fields_validators[$field_name])) {
        	$this->_fields_errors[$field_name] = $message;
        }
    }
    
    /**
     *
     * Returns vield value.
     * @param   string  $field_name
     * @param   boolean $just_escape
     * @return  string
     * @access  public 
     *
     */
    function getValue($field_name, $just_escape = false) {
        if (!isset($this->_fields_values[$field_name]) || empty($this->_fields_values[$field_name])) {
        	return null;
        }
        
        if (is_array($this->_fields_values[$field_name])) {
            $_value = array();
        	foreach ($this->_fields_values[$field_name] as $_field_value_key => $_field_value_value) {
        		$_value[$_field_value_key] = $just_escape ? Fire_Sanitize::escapeStr($_field_value_value) : Fire_Sanitize::normalize($_field_value_value);
        	}
        } else {
            $_value = '';
            $_value = $just_escape ? Fire_Sanitize::escapeStr($this->_fields_values[$field_name]) : Fire_Sanitize::normalize($this->_fields_values[$field_name]);
        }
        
        return $_value;
    }
    
    /**
     *
     * Returns field error message.
     * @param   string  $field_name
     * @return  string
     * @access  public 
     *
     */
    function getError($field_name) {
        return isset($this->_fields_errors[$field_name]) ? $this->_fields_errors[$field_name] : '';
    }
    
    /**
     *
     * Returns validation state of the field.
     * @param   string  $field_name
     * @return  boolean
     * @access  public 
     *
     */
    function isValid($field_name) {
        return isset($this->_fields_errors[$field_name]) ? false : true;
    }
    
    /**
     *
     * Accepts a model class as argument and registers it's fields in the form.
     * @param   Fire_Model  $model
     * @param   Fire_Locale $lc
     * @param   mixed
     * @access  public 
     *
     */
    function registerFromModel(&$model, &$lc, $without_collumns = array('id', 'time_created', 'time_updated', 'updated_by')) {
        
        $columns = $model->_db->_connection->MetaColumns($model->table);
        
        while (list(, $column) = each($columns)) {
            
            if (in_array($column->name, $without_collumns)) {
            	continue;
            }
            
        	switch ($column->type) {
        	    case 'tinyint':
        	        $_min = -127;
        	        $_max = 127;
        	    case 'smallint':
//        	        32768
        	    case 'int':
        	        $this->register($column->name, new IntegerValidator(1, $column->max_length), $column->not_null ? false : true);
        	    case 'varchar':
        	        $this->register($column->name, new StringValidator(1, $column->max_length), $column->not_null ? false : true);
        	        break;
        	    case 'char':
        	        $this->register($column->name, new BooleanValidator($column->not_null ? true : false), $column->not_null ? false : true);
        	        break;
        	    case 'longtext':
        	        $this->register($column->name, new TextValidator(1, $column->max_length), $column->not_null ? false : true);
        	        break;
    	        case 'datetime':
        	        $this->register($column->name, new DateValidator($lc->get('base_date_format'), $lc->get('base_date_separator')), $column->not_null ? false : true);
        	        break;
        	}
        }
    }
}
?>