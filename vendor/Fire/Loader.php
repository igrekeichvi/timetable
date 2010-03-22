<?php
/**
 *  This file contains class for handling loading of files.
 *  @package    Library
 */

/** Requires object class. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  A loader class.
 *  @package    Library
 */
class Fire_Loader extends Fire_Object {
    
    /**
     *
     * Loads a file.
     * @param   string  $name
     * @param   mixed   $directories
     * @param   boolean $just_once
     * @param   boolean $throw
     * @access  public 
     *
     */
    static function loadFile($name, $directories = array() , $just_once = true, $throw = false) {
        
        $path = sprintf('%s%s%s', implode(DIRECTORY_SEPARATOR, $directories), DIRECTORY_SEPARATOR, $name);
        $path = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
        
        if (preg_match('/[^a-z0-9\\\\\/\-_.]/i', $name) || !file_exists($path) || !is_readable($path)) {
            
            if ($throw) {
            	Fire_Error::throwError(sprintf('A file "%s" can not be loaded.', $path), __FILE__, __LINE__);
            }
            
            return false;
        }
        
        if ($just_once === true) {
        	require_once($path);
        } else {
            require($path);
        }
        
        return true;
    }
    
    /**
     *
     * Loads a class.
     * @param   string  $class_name
     * @param   string  $file_name
     * @param   mixed   $directories
     * @param   boolean $throw
     * @access  public 
     *
     */
    static function loadClass($class_name, $file_name, $directories = array(), $throw = false) {
        
        if (class_exists($class_name)) {
            return true;
        }
        
        if (!Fire_Loader::loadFile($file_name . '.php', $directories, true, $throw)) {
        	return false;
        }
        
        if (class_exists($class_name)) {
            return true;
        }
        
        if ($throw) {
        	Fire_Error::throwError(sprintf('A class "%s" can not be loaded.', $class_name), __FILE__, __LINE__);
        }
        
        return false;
    }
    
    /**
     *
     * Loads a library.
     * @param   string  $library_name
     * @param   boolean $with_instance
     * @return  mixed
     * @access  public 
     *
     */
    static function library($library_name, $with_instance = false) {
        
        $_library_name = 'Fire_' . ucfirst($library_name);
        
        if (Fire_Loader::loadClass($_library_name, ucfirst($library_name), array(FIRE_LIBRARY_PATH), true)) {
        	if ($with_instance) {
        		return new $_library_name(); 
        	}
        }
        
        return null;
    }
    
    /**
     *
     * Loads a helper.
     * @param   string  $helper_name
     * @param   boolean $with_instance
     * @return  mixed
     * @access  public 
     *
     */
    static function helper($helper_name, $with_instance = false) {
        
        $_name = 'Fire_' . ucfirst($helper_name) . '_Helper';
        
        if (Fire_Loader::loadClass($_name, ucfirst($helper_name), array(FIRE_LIBRARY_PATH, 'Helpers'), true)) {
        	if ($with_instance) {
        		return new $_name(); 
        	}
        }
        
        return null;
    }
    
    /**
     *
     * Loads a model.
     * @param   string  $model_name
     * @param   boolean $with_instance
     * @param   boolean $auto_connect
     * @return  Fire_Model
     * @access  public 
     *
     */
    static function model($model_name, $with_instance = false, $auto_connect = false) {
        
        if (Fire_Loader::loadClass($model_name, $model_name, array(FIRE_APPLICATION_PATH, 'models'), true)) {
        	if ($with_instance) {
        		$model = new $model_name();
        		
        		if ($auto_connect) {
        			$model->database();
        		}
        		
        		return $model;
        	}
        }
        
        return null;
    }
}
?>