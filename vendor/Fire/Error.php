<?php
/**
 *  This file contains class for handling errors.
 *  @package    Library
 */

/** Requires object class. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/** Let's define our constant to cancel the error. */
define('FIRE_CANCEL_ERROR', E_USER_NOTICE);

/** Handle ADO error message if not defined. */
if (!defined('ADODB_ERROR_HANDLER_TYPE'))   define('ADODB_ERROR_HANDLER_TYPE', FIRE_CANCEL_ERROR);

/**
 *  Class for handling error.
 *  @package    Library
 */
class Fire_Error extends Fire_Object {
    
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
     * Throws an exception,
     * @param   string  $message
     * @param   string  $file
     * @param   string  $line
     * @param   boolean $critical
     * @access  public 
     * @static 
     *
     */
    static function throwError($message, $file = null, $line = null, $critical = true) {
        if ($critical) {
            Fire_Error::toPage('An error occured!', $message, $file, $line, true);
        	exit(1);
        }
        
        echo Fire_Error::toBlock($message, $file, $line);
    }
    
    /**
     *
     * Returns a debug information.
     * @param   string  $title
     * @param   string  $message
     * @param   boolean $trace_error
     * @access  public
     * @static 
     *
     */
    static function toPage($title, $message, $file = null, $line = null, $trace_error = true) {
        echo sprintf('<?xml version="1.0" encoding="iso-8859-1"?>
              <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
              <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
              <head>
              <title>%s</title>
              <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
              <style type="text/css" media="screen">
              /*<![CDATA[*/
              <!--
                  
              -->
              /*]]>*/
              </style>
              </head>
              <body>
                  %s
                  <div>
                    %s
                    %s
                  </div>
              </body>
              </html>',
                $title,
                Fire_Error::toBlock($message, $file, $line),
                $trace_error ? '<p>Debug:</p>' : '',
                $trace_error ? Fire_Error::getBacktrace() : ''
          );
    }
    
    /**
     *
     * Echoes error in simple block element.
     * @param   string  $message
     * @param   string  $file
     * @param   string  $line
     * @return  string
     * @access  public 
     *
     */
    static function toBlock($message, $file = null, $line = null) {
        return sprintf('%s<br/>
%s %s<br/>',
                $message,
                $file ? 'Shown in file: <a href="file:/' . $file . '">'. $file . '</a>' : '',
                $line ? ' at line: ' . $line : ''
          );
    }
    
    /**
     *
     * Prints the backtrace
     * @param  string $errorNumber
     * @param  string $errorString
     * @param  string $errorFile
     * @param  string $errorLine
     * @access public 
     * @static 
     *
     */
    static function getBacktrace() {
        $_vars = debug_backtrace();
        unset($_vars[0]);
        unset($_vars[1]);
        unset($_vars[2]);
        $_vars = array_reverse($_vars);
        $res = '<ul>';
        
        while (list($key, $value) = each($_vars)) {
            
            if ((isset($value['file']) && strpos($value['file'], 'Error.php') === false) &&
                (isset($value['function']) && strpos($value['function'], '__errorHandler') === false)
                ) {
        	   $args = '';
            	if (is_array($value['args'])) {
            		while(list(, $_t) = each($value['args'])) {
                		$args .= ', ';
                		switch (gettype($_t)) {
                			case 'integer':
                            case 'double':
                                $args .= $_t;
                                break;
                		    case 'string':
                		        $args .= sprintf('"%s"',
                		            htmlentities($_t)
                		        );
                                break;
                            case 'array':
                                $args .= sprintf('Array(%d)',
                                    count($_t)
                                );
                                break;
                            case 'object':
                                $args .= sprintf('Object(%s)',
                                    get_class($_t)
                                );
                                break;
                            case 'resource':
                                $args .= sprintf('Resource(%s)',
                                    strstr($_t, '#')
                                );
                                break;
                            case 'boolean':
                                $args .= ($_t ? 'true' : 'false');
                                break;
                            case 'NULL':
                                $args = 'null';
                                break;
                			default:
                			    $args .= 'Unknow';
                				break;
                		}
                	} // end while $_t
            	}
            	$res .= '<li>';
            	$res .= sprintf('
                    	   <p>
                    	       <strong>
                    	           file:
                    	       </strong>
                    	       %s
                    	       &mdash;
                    	       <strong>
                    	           line:
                    	       </strong>
                    	       %s
                    	   </p>
                    	   <p>
                    	       <strong>
                    	           call:
                    	       </strong>
                    	       %s%s%s(%s)
                    	   </p>
            	   ',
            	   isset($value['file']) ? $value['file'] : '',
            	   isset($value['line']) ? $value['line'] : '',
            	   isset($value['class']) ? $value['class'] : '',
            	   isset($value['type']) ? $value['type']  : '',
            	   isset($value['function']) ? $value['function'] : '',
            	   (strpos($args, ',') === 0) ? substr($args, 1, strlen($args)) : $args
            	);
            	$res .= '</li>';
            }
        }
    	
        $res .= '</ul>';
        return $res;
    }
}
/**
 *
 * Fire custom error handler.
 * @param   integer $level
 * @param   string  $message
 * @param   string  $file
 * @param   string  $line
 * @param   mixed   $context
 * @return  void
 * @access  private
 *
 */
function __errorHandler($level, $message, $file, $line, $context) {
    switch ($level) {
        case FIRE_CANCEL_ERROR:
            break;
        case ADODB_ERROR_HANDLER_TYPE:
            Fire_Error::throwError('DB error: '. $message, $file, $line, true);
            break;
        case E_WARNING:
            Fire_Error::throwError($message, $file, $line, false);
            break;
        case E_NOTICE:
            Fire_Error::throwError($message, $file, $line, false);
            break;
        default:
            Fire_Error::throwError($message, $file, $line, false);
    }
}
/** And set the error handler to ours. */
set_error_handler('__errorHandler');
?>