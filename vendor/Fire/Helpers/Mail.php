<?php
/**
 *  Mail class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads htmlMimeMail. */
require_once(sprintf('%1$s%2$sHelpers%2$sMail%2$shtmlMimeMail.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 *  Mail class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Mail_Helper extends htmlMimeMail {
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
    }
}
?>