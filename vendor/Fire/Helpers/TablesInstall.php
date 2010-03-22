<?php
/**
 *  Class for instaling tables to the databasde.
 *  @package    Helpers
 */

/** Loads error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Loads database. */
require_once(FIRE_LIBRARY_PATH . 'Database.php');

/**
 *  Class for instaling tables to the databasde.
 *  @package    Helpers
 */
class Fire_Tables_Install_Helper extends Fire_Object {
    
    /**
     * @var     mixed $_tables
     * @access  private 
     */
    var $_tables;
    
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
     * Loads the tables specification.
     * @param   string  $file
     * @access  public 
     *
     */
    function loadTables($file) {
        
        require_once($file);
        
        $this->_tables = $tables;
        unset($tables);
    }
    
    /**
     *
     * Creates database tables, written down in the tables specification file.
     * @param   string  $file
     * @return  boolean
     * @access  public 
     *
     */
    function install($file = null) {
        
        if (!is_null($file)) {
        	$this->loadTables($file);
        }
        
        $_adapter = Fire_Database::instance();
        $_dictionary = NewDataDictionary($_adapter->_connection);
        
        $_created_tables = $_adapter->_connection->MetaTables();
        $_current_result = true;
        while (list($_table_name, $_table_fields) = each($this->_tables)) {
            
            if (in_array($_adapter->prefix . $_table_name, $_created_tables)) {
            	
                $_table_columns = $_adapter->_connection->MetaColumnNames($_adapter->prefix . $_table_name);
                
                while (list(, $_column) = each($_table_columns)) {
                	if (!in_array($_column, array_keys($_table_fields['columns']))) {
                	    $_current_result = $_current_result && $_dictionary->ExecuteSQLArray(
                            $_dictionary->DropColumnSQL(
                                $_adapter->prefix . $_table_name, 
                                array($_column)
                            )
                	    );
                	}
                }
                
                $_current_result = $_current_result && $_dictionary->ExecuteSQLArray(
                    $_dictionary->ChangeTableSQL(
                        $_adapter->prefix . $_table_name,
                        array_values($_table_fields['columns']), 
                        !empty($_table_fields['options']) ? $_table_fields['options'] : false
                    )
            	);
                
            	$_current_result = $_current_result && $this->_modifyIndexes($_dictionary, $_adapter->prefix . $_table_name, $_table_fields['indexes']);
            	
            } else {
                
                // create table, as it doesn't exists
                $_current_result = $_current_result && $_dictionary->ExecuteSQLArray(
                    $_dictionary->CreateTableSQL(
                        $_adapter->prefix . $_table_name, 
                        array_values($_table_fields['columns']), 
                        !empty($_table_fields['options']) ? $_table_fields['options'] : false
                    )
                );
                
                if (!empty($_table_fields['indexes'])) {
                    $_current_result = $_current_result && $this->_createIndexes($_dictionary, $_adapter->prefix . $_table_name, $_table_fields['indexes']);
                }
                
                if (!empty($_table_fields['foreign_keys'])) {
                    $_current_result = $_current_result && $this->_createForeignKeys($_dictionary, $_adapter->prefix . $_table_name, $_table_fields['foreign_keys'], $_adapter->prefix);
                }
                
            } // end check if table exists
            
        } // end while there's tables
        
        return $_current_result;
    }
    
    /**
     *
     * Creates indexes.
     * @param   mixed   $dictionary
     * @param   string  $table_name
     * @param   mixed   $indexes
     * @return  boolean
     * @access  public 
     *
     */
    function _createIndexes(&$dictionary, $table_name, $indexes) {
        
        $_current_result = true;
                
    	while (list($_index_name, $_index) = each($indexes)) {
            $_current_result = $_current_result && $dictionary->ExecuteSQLArray(
                $dictionary->CreateIndexSQL(
                    $_index_name,
                    $table_name,
                    $_index[0],
                    !empty($_index[1]) ? $_index[1] : false
                )
    	    );
    	    
    	}
        
    	return $_current_result;
    }
    
    /**
     *
     * Updates the indexes.
     * @param   mixed   $dictionary
     * @param   string  $table_name
     * @param   mixed   $indexes
     * @return  boolean
     * @access  private 
     *
     */
    function _modifyIndexes(&$dictionary, $table_name, $indexes) {
        
        $_current_result = true;
        
        $created_indexes = $dictionary->MetaIndexes($table_name);
        
        while (list($index,) = each($created_indexes)) {
        	if (!in_array($index, array_keys($indexes))) {
        		$_current_result = $_current_result && $dictionary->ExecuteSQLArray(
                    $dictionary->DropIndexSQL(
                        $index,
                        $table_name
                    )
        		);
        	}
        }
        
        while (list($_index_name, $_index) = each($indexes)) {
        	if (!in_array($_index_name, array_keys($created_indexes))) {
        		$_current_result = $_current_result && $dictionary->ExecuteSQLArray(
                    $dictionary->CreateIndexSQL(
                        $_index_name,
                        $table_name,
                        $_index[0],
                        !empty($_index[1]) ? $_index[1] : false
                    )
        	    );
        	}
        }
        
        return $_current_result;
    }
    
    /**
     *
     * Creates the foreign keys and corresponding to them indexes.
     * @param   mixed   $dictionary
     * @param   string  $table_name
     * @param   mixed   $foreign_keys
     * @param   string  $tables_prefix
     * @return  boolean
     * @access  public 
     *
     */
    function _createForeignKeys(&$dictionary, $table_name, $foreign_keys, $tables_prefix) {
        
        $_current_result = true;
        
        while (list($_foreign_key_name, $_foreign_key) = each($foreign_keys)) {
            		
    		$_current_result = $_current_result && $dictionary->connection->Execute(
                sprintf('ALTER TABLE %s ADD CONSTRAINT %s FOREIGN KEY (%s) REFERENCES %s (%s) %s',
                    $table_name,
                    $_foreign_key_name,
                    $_foreign_key[2],
                    $tables_prefix . $_foreign_key[0],
                    $_foreign_key[1],
                    $_foreign_key[3]
                )
    		);
    		
    	} // end creating foreign keys
    	
    	return $_current_result;
    }
}
?>