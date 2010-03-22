<?php
/**
 *  This file contains database wrapper class.
 *  @package    Library
 */

/** Loads error. */
require_once(FIRE_LIBRARY_PATH . 'Error.php');
/** Loader */
require_once(FIRE_LIBRARY_PATH . 'Loader.php');
/** Config class */
require_once(FIRE_LIBRARY_PATH . 'Config.php');

/** Defining default database config file. */
if (!defined('FIRE_DATABASE_CONFIG_FILE')) {
	define('FIRE_DATABASE_CONFIG_FILE', sprintf('%1$s%2$sconfig%2$sdatabase.php', FIRE_APPLICATION_PATH, DIRECTORY_SEPARATOR));
}

/**
 *  Database abstraction class.
 *  @package    Library
 */
class Fire_Database extends Fire_Object {
    
    /**
     * @var     adodb_mysql $_connection
     * @access  private 
     */
    var $_connection;
    
    /**
     * @var     string $prefix
     * @access  public
     */
    var $prefix;
    
    /**
     *
     * Constructor.
     * @param   string  $server
     * @param   string  $hostname
     * @param   string  $username
     * @param   string  $password
     * @param   string  $database
     * @param   string  $collation
     * @param   string  $names
     * @return  void
     * @access  public 
     *
     */
    function __construct($server = '', $hostname = '', $username = '', $password = '', $database = '', $collation = '', $names = '') {
        parent::__construct();
        
        if (!strlen($server) || !strlen($hostname) || !strlen($username) || !strlen($password) || !strlen($database)) {
        	$config = new Fire_Config(FIRE_DATABASE_CONFIG_FILE);
        	$server = $config->get('db_type');
        	$hostname = $config->get('db_host');
        	$username = $config->get('db_username');
        	$password = $config->get('db_password');
        	$database = $config->get('db_database');
        	$names = $config->get('db_default_charset');
        	$collation = $config->get('db_collaction_connection');
        }

        Fire_Loader::loadFile('adodb.inc.php', array($config->get('path_to_adodb_absolute')), true);
        
        if ($config->get('db_log_errors')) {
            if (!defined('ADODB_ERROR_LOG_TYPE')) {
            	define('ADODB_ERROR_LOG_TYPE', 3);
            }
            
            if (!defined('ADODB_ERROR_LOG_DEST')) {
            	define('ADODB_ERROR_LOG_DEST', $config->get('db_file_to_log_errors'));
            }
        }
        
        if (!$config->get('db_report_errors')) {
            if (!defined('ADODB_ERROR_HANDLER_TYPE')) {
            	define('ADODB_ERROR_HANDLER_TYPE', FIRE_CANCEL_ERROR);
            }
        }
        
        Fire_Loader::loadFile('adodb-errorhandler.inc.php', array($config->get('path_to_adodb_absolute')), true);
        
        $this->_connection = NewADOConnection($server);
        $this->_connection->Connect($hostname, $username, $password, $database);
        
        if (!$this->_connection->IsConnected()) {
        	Fire_Error::throwError('Connection to the database failed!', __FILE__, __LINE__);
        }
        
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        $ADODB_COUNTRECS = false;
        
        $this->_connection->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->_connection->debug = $config->get('db_global_debug');
        
        $this->setCollation($collation);
        $this->setNames($names);
        
        $this->prefix = $config->get('db_prefix');
    }
    
    /**
     *
     * Singleton instance of database.
     * @param   string  $server
     * @param   string  $hostname
     * @param   string  $username
     * @param   string  $password
     * @param   string  $database
     * @param   string  $collation
     * @param   string  $names
     * @return  Fire_Database
     * @access  public 
     *
     */
    static function &instance($server = '', $hostname = '', $username = '', $password = '', $database = '', $collation = '', $names = '') {
        
        static $_instance;
        
        if (!isset($_instance)) {
        	$_instance = new Fire_Database($server, $hostname, $username, $password, $database, $collation, $names);
        }
        
        return $_instance;
    }
    
    /**
     *
     * Returns list of records.
     * @param   string  $table
     * @param   mixed   $fields
     * @param   mixed   $where
     * @param   integer $page
     * @param   integer $rows
     * @param   string  $order
     * @return  ADORecordSet
     * @access  public 
     *
     */
    function select($table, $fields = array(), $where = array(), $page = 1, $rows = ITEMS_PER_PAGE, $order = array('id DESC'), $group = array()) {
        
        if (is_array($fields) && count($fields) > 0) {
        	$_fields = implode(',', $fields);
        } else {
            $_fields = '*';
        }
        
        $_where = '';
        if (is_array($where) && count($where) > 0) {
            $_where = sprintf('AND %s', implode(' ', $where));
        }
        
        $_order = '';
        if (is_array($order) && count($order) > 0) {
        	$_order = implode(',', $order);
        }
        
        $_group = '';
        if (is_array($group) && count($group) > 0) {
        	$_group = implode(',', $group);
        }
        
        $_query = sprintf('SELECT %s
                 FROM %s
                 WHERE 1 %s
                 %s
                 %s',
            $_fields,
            $table,
            $_where,
            strlen($_group) ? 'GROUP BY ' . $_group : '',
            strlen($_order) ? 'ORDER BY ' . $_order : ''
        );
        
        if ($rows != ITEMS_UNLIMITED) {
            return $this->_connection->PageExecute($_query, $rows, $page);
        } else {
            return $this->_connection->Execute($_query);
        }
    }
    
    /**
     *
     * Performs a simple query to the database.
     * @param   string  $sql_string
     * @return  mixed
     * @access  public 
     *
     */
    function query($sql_string) {
        return $this->_connection->query($sql_string, array());
    }
    
    /**
     *
     * Selects a database for usage.
     * @param   string  $database
     * @access  public 
     *
     */
    function useDatabase($database = '') {
        if (strlen($database)) {
        	$this->_connection->query('USE ' . $database, array());
        }
    }
    
    /**
     *
     * Closes the connection.
     * @access  public 
     *
     */
    function close() {
        $this->_connection->Close();
    }
    
    /**
     *
     * Sets the collation of the connection.
     * @param   string  $collation
     * @access  public 
     *
     */
    function setCollation($collation = '') {
        if (strlen($collation)) {
        	$this->_connection->query('SET collation_connection = '.$collation, array());
        }
    }
    
    /**
     *
     * Sets the name table.
     * @param   string  $names
     * @access  public 
     *
     */
    function setNames($names = '') {
        if (strlen($names)) {
        	$this->_connection->query('SET NAMES ' . $names, array());
        }
    }
    
    /**
     *
     * Returns last insert ID.
     * @param   string  $table
     * @param   string  $column
     * @return  integer
     * @access  public 
     *
     */
    function getInsertID($table = '', $column = '') {
        return intval($this->_connection->Insert_ID($table, $column));
    }
    
    /**
     *
     * A destructor.
     * @access  public 
     *
     */
    function __destruct() {
        if ($this->_connection->IsConnected()) {
//        	$this->_connection->Close();
        }
    }
}
?>