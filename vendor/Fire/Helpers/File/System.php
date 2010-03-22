<?php
/**
 *  This file contains File system class.
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
 *  Files system manipulation class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_File_System_Helper extends Fire_File_Helper {
    
    /**
     *
     * Constructor.
     * @param  string $file
     * @access public 
     *
     */
    function __construct($file = null) {
        parent::__construct($file);
    }
    
    /**
     *
     * Creates an empty file. Use as static with specified file as parameter.
     * @param  string $file
     * @return boolean
     * @access public 
     *
     */
    function create($file = null) {
        $file = (isset($this->_file) && !$file) ? $this->_file : $file;
        
    	if (!Fire_File_Helper::exists($file)) {
            $io = new Fire_File_IO_Helper($file);
            $io->open('w+');
            $io->close();
        } else {
            Fire_Error::throwError(sprintf('File "%s" already exists.',
                    $file
                ), __FILE__, __LINE__
            );
        }
        
    }
    
    /**
     *
     * Renames a file. Use as static with specified file as parameter.
     * @param  string $old
     * @param  string $new
     * @access public 
     *
     */
    function rename($old, $new = null) {
        
        if (!$new) {
            $new = $old;
        	$old = $this->_file;
        }
        
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' && (strtolower($old) == strtolower($new))) {
            
            $rand = sprintf('%s%s%s%s%s',
                dirname($old),
                DIRECTORY_SEPARATOR,
                microtime(),
                rand(1, 999),
                basename($old)
            );
            
            if (!@rename($old, $rand)) {
                Fire_Error::throwError(sprintf('Failed to temporary rename "%s".',
                        $old
                    ), __FILE__, __LINE__
                );
            }
            $old = $rand;
        }
        
        if (!@rename($old, $new)) {
            Fire_Error::throwError(sprintf('Failed to rename "%s" to "%s".',
                    $old,
                    $new
                ), __FILE__, __LINE__
            );
        } 
    }
    
    /**
     *
     * Copies a file. Use as static with specified file as parameter.
     * @param  string $src
     * @param  string $dest
     * @access public 
     *
     */
    function copy($src, $dest = null) {
        
        if (!$dest) {
            $dest = $src;
        	$src = $this->_file;
        }
        
        if (Fire_File_Helper::isFile($src)) {
        	if (!@copy($src, $dest)) {
                Fire_Error::throwError(sprintf('Failed to copy "%s" to "%s".',
                        $src,
                        $dest
                    ), __FILE__, __LINE__
                );
            } 
        } else {
            Fire_Error::throwError(sprintf('"%s" was not found as valid file.',
                    $src
                ), __FILE__, __LINE__
            );
        }
    }
    
    /**
     *
     * Deletes a file.
     * @param  string $file
     * @access public
     *
     */
    function delete($file = null) {
        $file = (isset($this->_file) && !$file) ? $this->_file : $file;
        if (Fire_File_Helper::isFile($file)) {
            if (!@unlink($file)) {
            	Fire_Error::throwError(sprintf('Failed to delete file "%s".',
            	       $file
            	   ), __FILE__, __LINE__
            	);
            }
        } else {
            Fire_Error::throwError(sprintf('"%s" was not found as valid file.',
                    $file
                ), __FILE__, __LINE__
            );
        }
    }
}
?>