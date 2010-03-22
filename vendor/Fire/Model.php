<?php
/**
 *  Model class.
 *  @package    Library
 */

/** Loads database. */
require_once(FIRE_LIBRARY_PATH . 'Database.php');

/** Defining default rows per page. */
if (!defined('ITEMS_PER_PAGE')) {
	define('ITEMS_PER_PAGE', 10);
}

/** Defining unlimited rows per page. */
if (!defined('ITEMS_UNLIMITED')) {
	define('ITEMS_UNLIMITED', -1);
}

/**
 *  Model class.
 *  @package    Library
 */
class Fire_Model extends Fire_Object {
    
    /**
     * @var     Fire_Database $_db
     * @access  private 
     */
    var $_db;
    
    /**
     * @var     string $table
     * @access  public 
     */
    var $table;
    
    /**
     * @var     mixed $_pager
     * @access  private 
     */
    var $_pager = array();
    
    /**
     *
     * Constructor.
     * @param   boolean $auto_load
     * @access  public 
     *
     */
    function __construct($auto_load = false) {
        parent::__construct();
        $this->table = (isset($this->table) ? $this->table : strtolower(get_class($this)));
        
        if ($auto_load) {
        	$this->database();
        }
    }
    
    /** 
     *
     * Set database adapter.
     * @access  public 
     *
     */
    function database() {
        $this->_db =& Fire_Database::instance();
        $this->table = $this->_db->prefix . $this->table;
    }
    
    /**
     *
     * Finds a records.
     * @param   mixed   $fields
     * @param   mixed   $where
     * @param   integer $page
     * @param   integer $rows
     * @param   string  $order
     * @param   string  $table
     * @return  mixed
     * @access  public 
     *
     */
    function findAll($fields = array(), $where = array(), $page = 1, $rows = ITEMS_PER_PAGE, $order = array('id DESC'), $group = array('id'), $table = '') {
        
        $rs = $this->_find(
                strlen($table) ? $this->_db->prefix . $table : $this->table,
                $fields,
                $where,
                $page,
                $rows,
                $order,
                $group
        );
        
        /* @var rs ADORecordSet */
        if ($rs->EOF) {
        	return array();
        }
        
        if ($rows != ITEMS_UNLIMITED) {
        	$this->_pager = array(
                'current'   =>  $rs->_currentPage,
                'last'   =>  $rs->_lastPageNo,
                'rows'   =>  $rs->rowsPerPage
            );
        }
        
        return $rs->GetArray();
    }
    
    /**
     *
     * Returns pager for current find.
     * @return  mixed   
     * @access  public 
     *
     */
    function getPager() {
        return $this->_pager;
    }
    
    /**
     *
     * Performs a check if given field is unique in the model table.
     * @param   string  $field_name
     * @param   mixed   $check_value
     * @param   string  $table
     * @return  boolean
     * @access  public
     *
     */
    function unique($field_name, $check_value, $table = '') {
        $rs = $this->_find(
            strlen($table) ? $this->_db->prefix . $table : $this->table,
            array('id'),
            array(sprintf('%s=%s', $field_name, is_numeric($check_value) ? $check_value : $this->_db->_connection->qstr($check_value))),
            1, ITEMS_UNLIMITED
        );
        
        /* @var rs ADORecordSet */
        if ($rs->EOF) {
        	return true;
        }
        
        return false;
    }
    
    /**
     *
     * Finds one record.
     * @param   mixed   $primary_key
     * @param   mixed   $fields
     * @param   string  $fields
     * @return  ADORecordSet
     * @access  public 
     *
     */
    function find($primary_key = array(), $fields = array(), $table = '') {
        $rs = $this->_find(
            strlen($table) ? $this->_db->prefix . $table : $this->table,
            $fields,
            $primary_key,
            1,
            ITEMS_UNLIMITED,
            array(),
            array()
        );
        
        /* @var rs ADORecordSet */
        return $rs->GetArray();
    }
    
    /**
     *
     * Insert a record using auto execute.
     * @param   mixed   $fields_with_values
     * @param   string  $table
     * @return  ADORecordSet
     * @access  public 
     *
     */
    function insert($fields_with_values, $table = '') {
        return $this->_db->_connection->AutoExecute(strlen($table) ? $this->_db->prefix . $table : $this->table, $fields_with_values);
    }
    
    /**
     *
     * Updates a record using auto execute.
     * @param   mixed   $fields_with_values
     * @param   mixed   $where
     * @param   string  $table
     * @return  ADORecordSet
     * @access  public 
     *
     */
    function update($fields_with_values, $where, $table = '') {
        
        if (is_array($where)) {
        	$where = implode(',', $where);
        }
        
        return $this->_db->_connection->AutoExecute(strlen($table) ? $this->_db->prefix . $table : $this->table, $fields_with_values, 'UPDATE', $where);
    }
    
    /**
     *
     * Deletes a record.
     * @param   mixed   $where
     * @param   string  $table
     * @return  ADORecordSet
     * @access  public 
     *
     */
    function delete($where, $table = '') {
        
        if (is_array($where)) {
        	$where = implode(',', $where);
        }
        
        return $this->_db->query(sprintf('DELETE FROM %s WHERE %s', strlen($table) ? $this->_db->prefix . $table : $this->table, $where));
    }
    
    /**
     *
     * Generates a list of value with IDs.
     * @param   string  $primary_key
     * @param   string  $field
     * @param   integer $page
     * @param   integer $rows
     * @param   string  $table
     * @return  mixed
     * @access  publuic 
     *
     */
    function generateList($primary_key, $field, $where = array(), $page = 1, $rows = ITEMS_UNLIMITED, $table = '') {
        $rs = $this->_find(($table ? $this->_db->prefix . $table : $this->table), array($primary_key, $field), $where, $page, $rows, $order = $primary_key . ' DESC');
        
        /* @var rs ADORecordSet */
        if ($rs->EOF) {
        	return array();
        }
        
        $_list = array();
        while (!$rs->EOF) {
        	$_list[$rs->fields[$primary_key]] = $rs->fields[$field];
        	$rs->MoveNext();
        }
        
        return $_list;
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
    function insertId($table = '', $column = '') {
        return $this->_db->getInsertID((strlen($table) ? $this->_db->prefix . $table : ''), $column);
    }
    
    /**
     *
     * Returns prefix.
     * @return  string
     * @access  public 
     *
     */
    function prefix() {
        return $this->_db->prefix;
    }
    
    /**
     *
     * Finds a records.
     * @param   string  $table
     * @param   mixed   $fields
     * @param   mixed   $where
     * @param   integer $page
     * @param   integer $rows
     * @param   mixed   $order
     * @param   mixed   $group
     * @return  mixed
     * @access  private 
     *
     */
    function _find($table, $fields = array(), $where = array(), $page = 1, $rows = ITEMS_PER_PAGE, $order = array('id DESC'), $group = array('id')) {
        return $this->_db->select(
                $table,
                $fields,
                $where,
                $page,
                $rows,
                $order,
                $group
        );
    }
}
?>