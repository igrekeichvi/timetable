<?php
/**
 *  Form helper class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Form helper class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Form_Helper extends Fire_Object {
    
    /**
     * @var     Fire_Form $_form
     * @access  private 
     */
    var $_form;
    
    /**
     * @var     Fire_Locale $_lc
     * @access  private 
     */
    var $_lc;
    
    /**
     * @var     string $_error_tag
     * @access  private 
     */
    var $_error_tag;
    
    /**
     *
     * Constructor.
     * @param   Fire_Form   $form
     * @param   Fire_Locale $lc
     * @param   string      $error_tag
     * @access  public 
     *
     */
    function __construct(&$form, &$lc, $error_tag = 'p') {
        parent::__construct();
        $this->_form =& $form;
        $this->_lc =& $lc;
        $this->_error_tag = $error_tag;
    }
    
    /**
     *
     * Returns a input field. It's used most of the validators.
     * @param   string  $field_name
     * @param   string  $accesskey
     * @param   integer $tabindex
     * @param   boolean $is_password
     * @param   boolean $title
     * @param   string  $explain
     * @return  string
     * @access  public 
     *
     */
    function string($field_name, $accesskey = '', $tabindex = 0, $is_password = false, $title = false, $explain = '') {
        
        if (!isset($this->_form->_fields_validators[$field_name])) {
        	return '';
        }
        
        return sprintf(
            '%1$s
            <p>
                <label for="%2$s"%3$s>%4$s%11$s</label>
                <input type="%5$s" name="%2$s" id="%2$s" value="%6$s" maxlength="%7$d"%8$s%9$s />
                %10$s
            </p>
            ',
            $this->getErrorTag($field_name, (isset($this->_form->_fields_validators[$field_name]['validator']->max_length) && (strlen($this->_form->getValue($field_name)) > $this->_form->_fields_validators[$field_name]['validator']->max_length)) ? $this->_form->_fields_validators[$field_name]['validator']->max_length : (isset($this->_form->_fields_validators[$field_name]['validator']->min_length) ? $this->_form->_fields_validators[$field_name]['validator']->min_length : '')),
            $field_name,
            strlen($accesskey) ? sprintf(' accesskey="%s"', $accesskey) : '',
            $this->_lc->get('label_for_' . $field_name),
            $is_password ? 'password' : 'text',
            $this->_form->getValue($field_name),
            isset($this->_form->_fields_validators[$field_name]['validator']->max_length) ? $this->_form->_fields_validators[$field_name]['validator']->max_length : '255',
            $tabindex > 0 ? sprintf(' tabindex="%d" ', $tabindex) : '',
            $title ? sprintf(' title="%s"', $this->_lc->get('title_for_field_'.$field_name)) : '',
            $explain ? sprintf('<span class="explain">%s</span>', $this->_lc->get('caption_for_max_symbols_allowed', $this->_form->_fields_validators[$field_name]['validator']->max_length)) : '',
            $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
        );
    }
    
    /**
     *
     * Returns textarea field. Text validator.
     * @param   string  $field_name
     * @param   string  $accesskey
     * @param   integer $tabindex
     * @param   boolean $title
     * @return  string
     * @access  public 
     *
     */
    function text($field_name, $accesskey = '', $tabindex = 0, $title = false) {
        
        if (!isset($this->_form->_fields_validators[$field_name])) {
        	return '';
        }
        
        return sprintf(
            '%1$s
            <p class="texts">
                <label for="%2$s"%3$s>%4$s%8$s</label>
                <textarea rows="5" cols="35" name="%2$s" id="%2$s"%6$s%7$s>%5$s</textarea>
            </p>
            ',
            $this->getErrorTag($field_name, strlen($this->_form->getValue($field_name)) > $this->_form->_fields_validators[$field_name]['validator']->max_length ? $this->_form->_fields_validators[$field_name]['validator']->max_length : $this->_form->_fields_validators[$field_name]['validator']->min_length),
            $field_name,
            strlen($accesskey) ? sprintf(' accesskey="%s"', $accesskey) : '',
            $this->_lc->get('label_for_' . $field_name),
            $this->_form->getValue($field_name),
            $tabindex > 0 ? sprintf(' tabindex="%d" ', $tabindex) : '',
            $title ? sprintf(' title="%s"', $this->_lc->get('title_for_field_'.$field_name)) : '',
            $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
        );
    }
    
    /**
     *
     * Returns boolean.
     * @param   string  $field_name
     * @param   string  $accesskey_yes
     * @param   string  $accesskey_no
     * @param   integer $tabindex
     * @param   boolean $title
     * @return  string
     * @access  public 
     *
     */
    function boolean($field_name, $accesskey_yes = '', $accesskey_no = '', $tabindex = 0, $title = false) {
        
        if (!isset($this->_form->_fields_validators[$field_name])) {
        	return '';
        }
        
        if ($this->_form->_fields_validators[$field_name]['validator']->must_choose) {
        	
            return sprintf(
                '%1$s
                <dl>
                    <dt>%2$s%14$s</dt>
                        <dd>
                            <label for="%3$s_yes"%4$s>%5$s</label>
                            <input type="radio" value="1" name="%3$s" id="%3$s_yes" %6$s%7$s%12$s/>
                        </dd>
                        <dd>
                            <label for="%3$s_no"%8$s>%9$s</label>
                            <input type="radio" value="0" name="%3$s" id="%3$s_no" %10$s%11$s%13$s/>
                        </dd>
                </dl>
                ',
                $this->getErrorTag($field_name),
                $this->_lc->get('labels_for_' . $field_name . '_choises'),
                $field_name,
                strlen($accesskey_yes) ? sprintf(' accesskey="%s" ', $accesskey_yes) : '',
                $this->_lc->get('label_for_' . $field_name . '_yes'),
                $tabindex > 0 ? sprintf('tabindex="%d " ', $tabindex) : '',
                $this->_form->getValue($field_name) ? 'checked="checked" ' : '',
                strlen($accesskey_no) ? sprintf(' accesskey="%s" ', $accesskey_no) : '',
                $this->_lc->get('label_for_' . $field_name . '_no'),
                $tabindex > 0 ? sprintf('tabindex="%d " ', $tabindex++) : '',
                $this->_form->getValue($field_name) ? '' : 'checked="checked" ',
                $title ? $this->_lc->get('title_for_field_' . $field_name . '_yes') : '',
                $title ? $this->_lc->get('title_for_field_' . $field_name . '_no') : '',
                $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
            );
            
        }
        
        return sprintf(
            '%1$s
            <p class="checkboxes">
                <input type="checkbox" value="1" name="%2$s" id="%2$s" %5$s%6$s/>
                <label for="%2$s"%3$s>%4$s%7$s</label>
            </p>
            ',
            $this->getErrorTag($field_name),
            $field_name,
            strlen($accesskey_yes) ? sprintf(' accesskey="%s" ', $accesskey_yes) : '',
            $this->_lc->get('label_for_' . $field_name),
            $tabindex > 0 ? sprintf('tabindex="%d" ', $tabindex) : '',
            $this->_form->getValue($field_name) ? 'checked="checked" ' : '',
            $title ? $this->_lc->get('titel_for_field_' . $field_name) : '',
            $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
        );
    }
    
    /**
     *
     * Returns enum validator tag.
     * @param   string  $field_name
     * @param   mixed   $labels
     * @param   string  $accesskeys
     * @param   integer $tabindex
     * @return  string
     * @access  public 
     *
     */
    function enum($field_name, $labels = array(), $accesskeys = '', $tabindex = 0) {
        
        if (!isset($this->_form->_fields_validators[$field_name])) {
        	return '';
        }
        
        if (strlen($accesskeys)) {
        	$accesskeys = explode(',', $accesskeys);
        }
        
        $_tag = $this->getErrorTag($field_name);
        $_tag .= "\n";
        
        if (count($this->_form->_fields_validators[$field_name]['validator']->range) > 4) {
        	
            $_tag .= "<p>\n";
            
            $_tag .= sprintf('
                <label for="%1$s"%5$s>
                    %6$s%7$s
                </label>
                <select name="%1$s%2$s" id="%1$s"%3$s%4$s>', 
                $field_name,
                $this->_form->_fields_validators[$field_name]['validator']->multiple ? '[]' : '',
                $tabindex > 0 ? sprintf(' tabindex="%d"', $tabindex) : '',
                $this->_form->_fields_validators[$field_name]['validator']->multiple ? 'multiple="multiple"' : '',
                isset($accesskeys[0]) ? sprintf(' accesskey="%s" ', trim($accesskeys[0])) : '',
                $this->_lc->get('label_for_' . $field_name),
                $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
            );
            
            $_tag .= "\n";
            $_tag .= sprintf('
                    <option value="">%s</option>', $this->_lc->get('labels_for_please_select_' . $field_name));
            $_tag .= "\n";
            
            while (list($element_key, $enum_element) = each($this->_form->_fields_validators[$field_name]['validator']->range)) {
                $_element = sprintf('%s_%s', $field_name, $enum_element);
                $_tag .= sprintf('
                    <option value="%1$s"%2$s>
                        %3$s
                    </option>
                    ',
                    $enum_element,
                    is_array($this->_form->getValue($field_name)) ? (in_array($enum_element, $this->_form->getValue($field_name)) ? 'selected="selected"' : '') : ($enum_element == $this->_form->getValue($field_name) ? ' selected="selected" ' : ''),
                    isset($labels[$enum_element]) ? Fire_Sanitize::normalize($labels[$enum_element]) : $this->_lc->get('label_for_' . $_element)
                );
            }
            
            $_tag .= "</select>\n";
            $_tag .= "</p>\n";
            return $_tag;
        }
        
        $_tag .= '<dl>';
        $_tag .= sprintf('<dt>%s%s</dt>', $this->_lc->get('labels_for_' . $field_name . '_choises'), $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>'));
        
        while (list($element_key, $enum_element) = each($this->_form->_fields_validators[$field_name]['validator']->range)) {
            
            $_element = sprintf('%s_%s', $field_name, $enum_element);
            $_tag .= sprintf('
                <dd>
                    <input type="%3$s" name="%1$s%2$s" id="%4$s" value="%7$s" %8$s %9$s/>
                    <label for="%4$s"%5$s>%6$s</label>
                </dd>
                ',
                $field_name,
                $this->_form->_fields_validators[$field_name]['validator']->multiple ? '[]' : '',
                $this->_form->_fields_validators[$field_name]['validator']->multiple ? 'checkbox' : 'radio',
                $_element,
                isset($accesskeys[$element_key]) ? sprintf(' accesskey="%s" ', trim($accesskeys[$element_key])) : '',
                isset($labels[$enum_element]) ? Fire_Sanitize::normalize($labels[$enum_element]) : $this->_lc->get('label_for_' . $_element),
                $enum_element, //$this->getValue($field_name),
                $tabindex > 0 ? sprintf('tabindex="%d" ', $tabindex++) : '',
                is_array($this->_form->getValue($field_name)) ? (in_array($enum_element, $this->_form->getValue($field_name)) ? 'checked="checked"' : '') : ($enum_element == $this->_form->getValue($field_name) ? 'checked="checked" ' : '')
            );
        }
        
        $_tag .= '</dl>';
        return $_tag;
    }
    
    /**
     *
     * Returns a field field.
     * @param   string  $field_name
     * @param   string  $accesskey
     * @param   integer $tabindex
     * @param   boolean $title
     * @param   string  $explain
     * @return  string
     * @access  public 
     *
     */
    function file($field_name, $accesskey = '', $tabindex = 0, $title = false, $explain = '') {
        
        if (!isset($this->_form->_fields_validators[$field_name])) {
        	return '';
        }
        
        return sprintf(
            '%1$s
            <p class="file_field">
                <label for="%2$s"%3$s>%4$s%9$s</label>
                <input type="%5$s" name="%2$s" id="%2$s" %6$s%7$s/>
                %8$s
            </p>
            ',
            $this->getErrorTag($field_name, implode(', ', $this->_form->_fields_validators[$field_name]['validator']->extensions)),
            $field_name,
            strlen($accesskey) ? sprintf(' accesskey="%s"', $accesskey) : '',
            $this->_lc->get('label_for_' . $field_name),
            'file',
            $tabindex > 0 ? sprintf(' tabindex="%d" ', $tabindex) : '',
            $title ? sprintf(' title="%s"', $this->_lc->get('title_for_field_'.$field_name)) : '',
            $explain ? sprintf('<span class="explain">%s</span>', $this->_lc->get('caption_for_allowed_file_formats', implode(', ', $this->_form->_fields_validators[$field_name]['validator']->extensions))) : '',
            $this->_form->_fields_validators[$field_name]['optional'] == true ? '' : sprintf('<span class="required">*</span>')
        );
    }
    
    /**
     *
     * Returns field error message.
     * @param   string  $field_name
     * @param   string  $info
     * @return  string
     * @access  public 
     *
     */
    function getErrorTag($field_name, $info = '') {
        return isset($this->_form->_fields_errors[$field_name]) ? sprintf('<%2$s class="error" id="%3$s_error">%1$s</%2$s>', $this->_lc->get($this->_form->_fields_errors[$field_name], $info), $this->_error_tag, $field_name) : '';
    }
    
    /**
     *
     * Returns whole form.
     * @return  string
     * @access  public 
     *
     */
    function fields() {
        foreach ($this->_form->_fields_validators as $field_name => $validator) {
        	switch (str_replace('validator', '', strtolower($validator['validator']->getName()))) {
        	    case 'date':
        	    case 'double':
        	    case 'email':
        	    case 'integer':
        	    case 'ip':
        	    case 'phone':
        	    case 'url':
        	    case 'string':
        	        echo $this->string($field_name);
        	        break;
        	    case 'text':
        	        echo $this->text($field_name);
    	           break;
        	    case 'boolean':
        	        echo $this->boolean($field_name);
        	        break;
        	    case 'enum':
        	        echo $this->enum($field_name);
        	        break;
        	}
        }
    }
}
?>