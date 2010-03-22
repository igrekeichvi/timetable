<?php
/**
 *  This file contains class for Filtering strings.
 *  @package    Library
 */

/** Requires object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Class for filtering string.
 *  @package    Library
 */
class Fire_Sanitize extends Fire_Object {
    
    /**
     *
     * Constructor.
     * @return  void
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
    }
    
    /**
     * 
     * Removes the Javascript code from a string.
     * @param   string $text The text we want to filter
     * @return  string $text Returns the filtered text
     * @access  public
     * @static 
     * 
     */
    static function filterJS($text) {
        /** remove tags */
        $text = preg_replace('/<SCRIPT.*?<\/SCRIPT>/ims','',$text);
        /** remove events */
        $text = preg_replace('/on(Load|Click|DblClick|DragStart|KeyDown|KeyPress|KeyUp|MouseDown|MouseMove|MouseOut|MouseOver|SelectStart|Blur|Focus|Scroll|Select|Unload|Change|Submit)\s*=\s*(\'|").*?\\2/smi','',$text);
        $text = preg_replace('/(\'|")Javascript:.*?\\1/smi','',$text);
        return $text;
    }
    
    /**
     *
     * Removes empty space and gives some slashes to a given string.
     * In short this method prepares string for database queries.
     * @param   string  $string
     * @return  string
     * @access  public
     * @static 
     *
     */
    static function escapeStr($string) {
        
        if (!get_magic_quotes_gpc()) {
        	$string = addslashes($string);
        }
        
        return Fire_Sanitize::reverse(Fire_Sanitize::filterJS(preg_replace('/ +/', ' ', $string)));
    }
    
    /**
     * 
     * Filter a string: remove slashes, js etc.
     * @param   string  $string
     * @param   boolean $convert_new_lines
     * @return  string
     * @access  public
     * @static 
     */
    static function filterStr($string, $convert_new_lines = false) {
        
        if ($convert_new_lines) {
        	$string = nl2br($string);
        }
        
        return Fire_Sanitize::filterJS(stripslashes($string));
    }

    /**
     *
     * Converts characters to their entities.
     * @param   string  $string
     * @return  string
     * @access  pubic 
     * @static 
     *
     */
    static function entityString($string) {
        
        $patterns = array(    '/\&/',   '/%/',  '/</',  '/>/',  '/"/',    '/\'/',  '/\(/',  '/\)/',  '/\+/',  '/-/',   '/\?/');
		$replacements = array('&amp;',  '&#37;','&lt;', '&gt;', '&quot;', '&#39;', '&#40;', '&#41;', '&#43;', '&#45;', '&#63;');
		
		return preg_replace($patterns, $replacements, $string);
    }
    
    /**
     *
     * Reverses entity string.
     * @param   string  $string
     * @return  string
     * @access  public 
     *
     */
    static function reverse($string) {
        $replacements = array('&', '%', '<', '>', '"', '\'', '(', ')', '+', '-', '?');
		$patterns = array('/\&amp;/', '/\&\#37;/', '/\&lt;/', '/\&gt;/', '/\&quot;/', '/\&\#39;/', '/\&\#40;/', '/\&\#41;/', '/\&\#43;/', '/\&\#45;/', '/\&\#63;/');
		
		return preg_replace($patterns, $replacements, $string);
    }
    
    /**
     *
     * Normalizes given string,.
     * @param   string  $string
     * @param   boolean $convert_new_lines
     * @param   boolean $xss_clean
     * @return  string
     * @access  public 
     *
     */
    static function normalize($string, $convert_new_lines = true, $xss_clean = false) {
        $string = Fire_Sanitize::entityString(Fire_Sanitize::filterStr($string, $convert_new_lines));
        
        if ($xss_clean) {
        	$string = Fire_Sanitize::XSSClean($string);
        }
        
        return $string;
    }
    
    /**
     *
     * Cleans given string.
     * @param   string  $str
     * @return  string
     * @access  public 
     *
     */
    static function XSSClean($str) {
        
		$str = preg_replace('/\0+/', '', $str);
		$str = preg_replace('/(\\\\0)+/', '', $str);
		$str = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"\\1;",$str);
		$str = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"\\1\\2;",$str);

		/*
		 * URL Decode
		 */	
		$str = preg_replace("/%u0([a-z0-9]{3})/i", "&#x\\1;", $str);
		$str = preg_replace("/%([a-z0-9]{2})/i", "&#x\\1;", $str);		
		$str = preg_replace("#\t+#", " ", $str);
		$str = str_replace(array('<?php', '<?PHP', '<?', '?>'),  array('&lt;?php', '&lt;?PHP', '&lt;?', '?&gt;'), $str);

		$words = array('javascript', 'vbscript', 'script', 'applet', 'alert', 'document', 'write', 'cookie', 'window');
		foreach ($words as $word)
		{
			$temp = '';
			for ($i = 0; $i < strlen($word); $i++)
			{
				$temp .= substr($word, $i, 1)."\s*";
			}
			
			$temp = substr($temp, 0, -3);
			$str = preg_replace('#'.$temp.'#s', $word, $str);
			$str = preg_replace('#'.ucfirst($temp).'#s', ucfirst($word), $str);
		}

		$str = preg_replace('#<(/*\s*)(alert|applet|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|iframe|input|layer|link|meta|object|plaintext|style|script|textarea|title|xml|xss)([^>]*)>#is', "&lt;\\1\\2\\3&gt;", $str);
		$str = preg_replace('#(alert|cmd|passthru|eval|exec|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si', "\\1\\2&#40;\\3&#41;", $str);
						
		$bad = array(
			'document.cookie'	=> '',
			'document.write'	=> '',
			'window.location'	=> '',
			"javascript\s*:"	=> '',
			"Redirect\s+302"	=> '',
			'<!--'				=> '&lt;!--',
			'-->'				=> '--&gt;'
		);
		foreach ($bad as $key => $val) {
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		
		// SQL safety
		$str = str_replace('OR 1', '', $str);
		$str = str_replace('AND 1', '', $str);
		
		return $str;
    }
    
    /**
     *
     * Jsons the string.
     * @param   string  $response
     * @return  string
     * @access  public 
     *
     */
    static function json($response) {
		$response = preg_replace('/\n+/', '', $response);
		$response = str_replace('\\', '\\\\', $response);
		$response = str_replace('"', '\"', $response);
		return $response;
    }
    
    /**
     *
     * Qoutes the string
     * @param   string  $string
     * @return  string
     * @access  public 
     *
     */
    static function qstr($string) {
        return sprintf('\'%s\'', $string);
    }
    
    /**
     *
     * Cleans WYSIWYG contents.
     * @param   string  $string
     * @param   boolean $strict_clean
     * @return  string
     * @access  public 
     *
     */
    static function wysiwgClean($string, $strict_clean = false) {
        
        $string = str_replace('&', '&amp;', $string);
        
        // take care of the '>' and '<' that don't belong to any tag
        $string = preg_replace("/(\s+)(<)(\s+)/", "\\1&lt;\\3", $string );
        $string = preg_replace("/(\[^A-Za-z]+)(<)([^A-Za-z]+)/", "\\1&lt;\\3", $string );		      
        $string = preg_replace("/(\s+)(>)(\s+)/", "\\1&gt;\\3", $string );		  
        
		$patterns     = array('/%/',   '/"/',    '/\'/',  '/\(/',  '/\)/',  '/\+/',  '/-/',   '/\?/');
		$replacements = array('&#37;', '&quot;', '&#39;', '&#40;', '&#41;', '&#43;', '&#45;', '&#63;');

		if (!$strict_clean) {
		 	return preg_replace($patterns, $replacements, $string);
        }
        
        return Fire_Sanitize::_strictClean(preg_replace($patterns, $replacements, $string));
    }
    
    /**
     *
     * Cleans contents of WYSIWG area.
     * @param   string  $string
     * @return  string
     * @access  public 
     *
     */
    static function _strictClean($string) {
        $_replacements = array(
            '&curren;' => '¤',
            '&brvbar;' => '¦',
            '&sect;' => '§',
            '&copy;' => '©',
            '&laquo;' => '«',
            '&not;' => '¬',
            '&shy;' => '­',
            '&reg;' => '®',
            '&deg;' => '°',
            '&plusmn;' => '±',
            '&micro;' => 'µ',
            '&para;' => '¶',
            '&middot;' => '·',
            '&raquo;' => '»',
            '&ndash;' => '–',
            '&mdash;' => '—',
            '&lsquo;' => '‘',
            '&rsquo;' => '’',
            '&sbquo;' => '‚',
            '&ldquo;' => '“',
            '&rdquo;' => '”',
            '&bdquo;' => '„',
            '&dagger;' => '†',
            '&Dagger;' => '‡',
            '&bull;' => '•',
            '&hellip;' => '…',
            '&permil;' => '‰',
            '&lsaquo;' => '‹',
            '&rsaquo;' => '›',
            '&euro;' => '€',
            '&trade;' => '™',
            '&#33;' => '!',
            '&#36;' => '$',
            '&#42;' => '*',
            '&#44;' => ',',
            '&#46;' => '.',
            '&#58;' => ':',
            '&#61;' => '=',
            '&#64;' => '@',
            '&#91;' => '[',
            '&#92;' => '\\',
            '&#93;' => ']',
            '&#94;' => '^',
            '&#95;' => '_',
            '&#96;' => '`',
            '&#123;' => '{',
            '&#124;' => '|',
            '&#125;' => '}',
            '&#126;' => '~'
        );
        
        foreach ($_replacements as $_entity => $_letter) {
            $string = str_replace($_letter, $_entity, $string);
        }
        
        return $string;
    }
}
?>