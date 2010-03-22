<?php
/**
 * This file contains File_Remote class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');

/** Loads file. */
require_once(sprintf('%1$s%2$sHelpers%2$sFile.php', FIRE_LIBRARY_PATH, DIRECTORY_SEPARATOR));

/**
 * Class for working with remote files.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_File_Remote_Helper extends Fire_File_Helper {
    
    /**
     *
     * Constructor.
     * @access  public 
     *
     */
    function __construct() {
        parent::__construct();
        Fire_Error::throwError('Not implemented!');
    }
}
?>