<?php
/**
 *  This file contains DirectoryIO class.
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
 *  Directories system manipulation class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Directory_System_Helper extends Fire_File_Helper {
    
    /**
     *
     * Constructor.
     * @param  string $file
     * @access public 
     *
     */
    function __construct($file = null) {
        $file = (substr($file, strlen($file - 1)) != DIRECTORY_SEPARATOR) ? $file.DIRECTORY_SEPARATOR : $file;
        parent::__construct($file);
        Fire_Error::throwError('Has to rewrite this class.');
    }
    
//    /**
//     *
//     * Creates a directory.
//     * @param  string  $file
//     * @param  integer $mode
//     * @access public 
//     *
//     */
//    function create($file, $mode = 0777) {
//        
//        $file = (isset($this->_file) && !$file) ? $this->_file : (substr($file, strlen($file - 1)) != RX_DIRECTORY_SEPARATOR) ? $file.RX_DIRECTORY_SEPARATOR : $file;
//        
//        if (!RX_File::exists($file)) {
//        	if (!@mkdir($file, $mode)) {
//        		RX_FileException::throw(020201, sprintf('Can\'t create directory %s with mode %d.',
//                        $file,
//                        $mode
//                    )
//        		);
//        	}
//        } else {
//            RX_FileException::throw(020202, sprintf('Directory %s already exists.',
//                    $file
//                )
//            );
//        }
//    }
//    
//    /**
//     *
//     * Renames a file. Use as static with specified file as parameter.
//     * @param  string $old
//     * @param  string $new
//     * @access public 
//     *
//     */
//    function rename($old, $new = null) {
//        
//        if (!$new) {
//            $new = (substr($old, strlen($old - 1)) != RX_DIRECTORY_SEPARATOR) ? $old.RX_DIRECTORY_SEPARATOR : $old;
//        	$old = $this->_file;
//        } else {
//            $old = (substr($old, strlen($old - 1)) != RX_DIRECTORY_SEPARATOR) ? $old.RX_DIRECTORY_SEPARATOR : $old;
//            $new = (substr($new, strlen($new - 1)) != RX_DIRECTORY_SEPARATOR) ? $new.RX_DIRECTORY_SEPARATOR : $new;
//        }
//        
//        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' && (strtolower($old) == strtolower($new))) {
//            
//            $rand = sprintf('%s%s%s%s%s',
//                dirname($old),
//                RX_DIRECTORY_SEPARATOR,
//                microtime(),
//                rand(1, 999),
//                basename($old)
//            );
//            
//            if (!@rename($old, $rand)) {
//                RX_FileException::throw(020203, sprintf('Failed to temporary rename %s',
//                        $old
//                    )
//                );
//            }
//            $old = $rand;
//        }
//        
//        if (!@rename($old, $new)) {
//            RX_FileException::throw(020204, sprintf('Failed to rename %s to %s.',
//                    $old,
//                    $new
//                )
//            );
//        }
//    }
//    
//    /**
//     *
//     * Copies a directory.
//     * @param  string $src
//     * @param  string $dest
//     * @access public 
//     *
//     */
//    function copy($src, $dest = null, $recursive = false, $withFiles = false) {
//        
//        if (!$dest) {
//        	$dest = (substr($src, strlen($src - 1)) != RX_DIRECTORY_SEPARATOR) ? $src.RX_DIRECTORY_SEPARATOR : $src;
//        	$src = $this->_file;
//        } else {
//            $src = (substr($src, strlen($src - 1)) != RX_DIRECTORY_SEPARATOR) ? $src.RX_DIRECTORY_SEPARATOR : $src;
//            $dest = (substr($dest, strlen($dest - 1)) != RX_DIRECTORY_SEPARATOR) ? $dest.RX_DIRECTORY_SEPARATOR : $dest;
//        }
//        
//        if (!RX_File::isDirectory($src)) {
//            RX_FileException::throw(020205, sprintf('Directory source %s is not a valid directory.',
//                    $src
//                )
//            );
//        }
//        
//        if (RX_File::isDirectory($dest)) {
//            RX_FileException::throw(020205, sprintf('Directory destination %s already exists.',
//                    $dest
//                )
//            );
//        }
//        
//    	RX_DirectorySystem::create($dest);
//        
//    	if ($withFiles || $recursive) {
//    	    
//    	    $io = new RX_DirectoryIO($src);
//    	    $files = $io->readContents();
//    	    $filesIt = $files->iterator();
//    	    $filesIt->first();
//    	    while ($file = $filesIt->next()) {
//    	        /* @var $file RX_File */
//    	    	if ($file->isDirectory()) {
//    	    	    
//    	    		RX_DirectorySystem::copy(
//                        $file->get(),
//                        sprintf('%s%s',
//                            $dest,
//                            $file->getName()
//                        ),
//                        $withFiles,
//                        $recursive
//    	    		);
//    	    		
//    	    	} else {
//    	    	    
//    	    	    RX_FileSystem::copy(
//                        $file->get(),
//                        sprintf('%s%s',
//                            $dest,
//                            $file->getName()
//                        )
//    	    	    );
//    	    	    
//    	    	}
//    	    }
//    	    
//        }
//    }
//    
//    /**
//     *
//     * Deletes a directory.
//     * @param  string  $file
//     * @param  boolean $withFiles
//     * @param  boolean $recursive
//     * @access public 
//     *
//     */
//    function delete($file = null, $withFiles = false, $recursive = false) {
//        $file = (isset($this->_file) && !$file) ? $this->_file : (substr($file, strlen($file - 1)) != RX_DIRECTORY_SEPARATOR) ? $file.RX_DIRECTORY_SEPARATOR : $file;
//        
//        if (!RX_File::isDirectory($file)) {
//            RX_FileException::throw(020206, sprintf('Directory source %s is not a valid directory.',
//                    $src
//                )
//            );
//        }
//        
//        if ($withFiles || $recursive) {
//    	    
//            $io = new RX_DirectoryIO($file);
//    	    $files = $io->readContents();
//    	    $filesIt = $files->iterator();
//    	    $filesIt->last();
//    	    
//    	    while ($_file = $filesIt->prev()) {
//    	        /* @var $file RX_File */
//    	    	if ($_file->isDirectory()) {
//    	    		RX_DirectorySystem::delete($_file->get(), $withFiles, $recursive);
//    	    	} else {
//    	    	    RX_FileSystem::delete($_file->get());
//    	    	}
//    	    }
//            
//        }
//        
//        if (!@rmdir($file)) {
//            RX_FileException::throw(020207, sprintf('Failed to delete directory %s.',
//                    $file
//                )
//            );
//        }
//        
//    }
}
?>