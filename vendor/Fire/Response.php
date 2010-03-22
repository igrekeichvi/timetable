<?php
/**
 * This file contains class to handle HTTP responses.
 * @package     Library
 */

/** Requires object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');
/** Requires error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');

/**
 * This class manages Http responses.
 * @package     Library
 */
class Fire_Response extends Fire_Object {
        
    /**
     * @var     integer $_response_code
     * @access  private 
     */
    var $_response_code;
    
    /**
     * @var     mixed   $_response_headers
     * @access  private 
     */
    var $_response_headers;
    
    /**
     * @var     string  $_response_body
     * @access  private 
     */
    var $_response_body = null;
    
    /**
     * @var     boolean $_headers_sent
     * @access  private 
     */
    var $_headers_sent = false;
    
    /**
     *
     * Constructor.
     * @param   integer $response_code
     * @param   mixed   $response_headers
     * @param   string  $response_body
     * @return  void
     * @access  public 
     *
     */
    function __construct($response_code = 200, $response_headers = array(), $response_body = '') {
        parent::__construct();
        $this->_response_code = $response_code;
        $this->_response_headers = $response_headers;
        $this->_response_body = $response_body;
    }
    
    /**
     *
     * Sets the response body.
     * @param   string  $contents
     * @access  public 
     *
     */
    function setResponseBody($contents) {
        $this->_response_body .= $contents;
    }
    
    /**
     *
     * Sets the response code.
     * @param   integer $response_code
     * @access  public 
     *
     */
    function setResponseCode($response_code) {
        $this->_response_code = $response_code;
    }
    
    /**
     *
     * Sends a redirect header.
     * @param   string  $url
     * @return  void
     * @access  public 
     *
     */
    function redirect($url) {
        if ($this->areHeadersSent()) {
        	Fire_Error::throwError('Headers already sent, can\'t redirect.');
        }
        header('HTTP/1.1 303 See Other');
        header("Location: ".$url);
        exit();
    }
    
    /**
     *
     * Adds header to be send.
     * @param   string  $reponse_header
     * @access  public 
     *
     */
    function addHeader($reponse_header) {
        array_push($this->_response_headers, $reponse_header);
    }
    
    /**
     *
     * Sends headers.
     * @access  public 
     *
     */
    function sendHeaders() {
        if ($this->areHeadersSent()) {
        	Fire_Error::throwError('Headers already sent.');
        }
        
        header(sprintf('HTTP/1.1 %d',$this->_response_code));
        
        while (list(, $header) = each($this->_response_headers)) {
        	header($header);
        }
        
        $this->_headers_sent = true;
    }
    
    /**
     *
     * Returns indication if reponse headers are already sent.
     * @return  boolean
     * @access  public 
     *
     */
    function areHeadersSent() {
        return $this->_headers_sent;
    }
    
    /**
     *
     * Sends to contents of the reponse, if HTTP header is not redirect.
     * @access  public 
     *
     */
    function sendContents() {
        print $this->_response_body;
    }
}
?>