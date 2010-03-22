<?php
/**
 *  Markup helper class.
 *  @package    Library
 *  @subpackage Helpers
 */

/** Loads object. */
require_once(FIRE_LIBRARY_PATH . 'Object.php');

/**
 *  Markup helper class.
 *  @package    Library
 *  @subpackage Helpers
 */
class Fire_Markup_Helper extends Fire_Object {
    
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
     * Echoes a <a> tag string.
     * @param   string  $uri
     * @param   string  $accesskey
     * @param   integer $tabindex
     * @param   string  $id
     * @param   mixed   $atrbs
     * @return  string
     * @access  public 
     *
     */
    static function ahref($uri = '', $caption = '', $title = '', $accesskey = '', $tabindex = 0, $id = '', $atrbs = array()) {
        
        if (!strlen($uri)) {
        	return '';
        }
        
        return sprintf('<a href="%s"%s%s%s%s%s>%s</a>', 
            $uri,
            strlen($accesskey) ? sprintf(' accesskey="%s"', $accesskey) : '',
            $tabindex > 0 ? sprintf(' tabindex="%d"', $tabindex) : '',
            strlen($title) ? sprintf(' title="%s"', $title) : '',
            strlen($id) ? sprintf(' id="%s"', $id) : '',
            Fire_Markup_Helper::_atrbs($atrbs),
            strlen($caption) ? $caption : '&nbsp;'
        );
    }
    
    /**
     *
     * Draws a table.
     * @param   mixed                   $result_list
     * @param   Fire_Locale             $lc
     * @param   Fire_RawRequest_Helper  $rg
     * @param   string                  $router
     * @param   string                  $with_managment_links
     * @param   mixed                   $without
     * @param   string                  $caption
     * @param   string                  $summary
     * @param   string                  $id
     * @param   string                  $class
     * @param   mixed                   $atrbs
     * @return  mixed
     * @access  public 
     *
     */
    static function table($result_list, &$lc, &$rg, $router = '', $tabindex = 0, $with_managment_links = '',  $row_primary_key = 'id', $without = array('id'), $caption = '', $summary = '', $id = '', $class = '', $atrbs = array()) {
        
        if (empty($result_list)) {
        	return '';
        }
        
        $_table = sprintf('<table cellspacing="0" cellpadding="0"%s%s%s>',
            strlen($summary) ? sprintf(' summary="%s"', $summary) : '',
            strlen($id) ? sprintf(' id="%s"', $id) : '',
            strlen($class) ? sprintf(' class="%s"', $class) : ''
        );
        
        $_table .= Fire_Markup_Helper::thead($result_list[0], $lc, strlen($with_managment_links) ? true : false, $without);
        
        $_table .= "<tbody>\n";
        
        while (list(, $table_row) = each($result_list)) {
            
            $_table .= "<tr>\n";
            while (list($field_name, $cell_data) = each($table_row)) {
                
                if (in_array($field_name, $without)) {
                	continue;
                }
                
            	$_table .= sprintf("<td>%s</td>\n",
                    $lc->san($cell_data)
                );
            }
            
            if (strlen($with_managment_links)) {
            	$_table .= sprintf('<td class="edit">%s</td>',
            	   Fire_Markup_Helper::ahref($rg->href($router, $with_managment_links .'edit',  $row_primary_key, $table_row['id']), $lc->get('caption_for_management_edit'), $lc->get(sprintf('caption_for_%s_edit', $with_managment_links)), '', $tabindex ? $tabindex++ : 0)
            	);
            	$_table .= sprintf('<td class="delete">%s</td>',
            	   Fire_Markup_Helper::ahref($rg->href($router, $with_managment_links .'delete',  $row_primary_key, $table_row['id']), $lc->get('caption_for_management_delete'), $lc->get(sprintf('caption_for_%s_delete', $with_managment_links)), '', $tabindex ? $tabindex++ : 0)
            	);
            }
            
            $_table .= "\n</tr>\n";
        }
        
        $_table .= "</tbody>\n";
        $_table .= "</table>\n";
        
        return $_table;
    }
    
    /**
     *
     * Table header.
     * @param   mixed   $header_row
     * @param   Fire_Locale $lc
     * @param   boolean $with_managment_links
     * @return  string
     * @access  public 
     *
     */
    static function thead($header_row, &$lc, $with_managment_links = false, $without = array('id')) {
        
        if (empty($header_row)) {
        	return '';
        }
        
        $_table = "\n<thead>\n";
        $_table .= "<tr>\n";
        $header_row = array_keys($header_row);
        while (list(, $table_head) = each($header_row)) {
            
            if (in_array($table_head, $without)) {
            	continue;
            }
            
            $_table .= sprintf('<th scope="col">%s</th>',
                $lc->get('caption_for_table_headings_' . $table_head)
            );
            $_table .= "\n";
        }
        
        if ($with_managment_links) {
        	$_table .= sprintf('<th colspan="2" scope="col" class="options"><span>%s</span></th>',
                $lc->get('caption_for_table_headings_management')
            );
            $_table .= "\n";
        }
        
        $_table .= "</tr>";
        $_table .= "\n</thead>\n";
        
        return $_table;
    }
    
    /**
     *
     * Generates the attributes.
     * @param   mixed   $atr
     * @return  string
     * @access  private
     *
     */
    static function _atrbs($atr = array()) {
        if (empty($atr)) {
        	return '';
        }
        
        $_res = ' ';
        while (list($key, $value) = each($atr)) {
        	$_res .= sprintf('%s="%s"', $key, $value);
        }
        
        return $_res;
    }
    
    /**
     *
     * Display the pager.
     * @param   Fire_Pager_Helper       $pager
     * @param   Fire_Locale             $lc
     * @param   Fire_RawRequest_Helper  $rg
     * @param   integer                 $tabindex
     * @param   string                  $router
     * @param   mixed                   $show_rows
     * @param   mixed                   $conditions
     * @param   boolean                 $_simple
     * @access  public 
     *
     */
    static function pager(&$pager, &$lc, &$rg, $tabindex = 0, $router = '', $show_rows = '', $conditions = array(), $_simple = false) {
        
        if ($pager->last < 2) {
        	return '';
        }
        
        $_pager = !$_simple ? '<ul id="pager">' : '';
        
        if ($pager->current > 1) {
        	$_pager .= sprintf('<li id="goto_first">%s</li>',
        	   Fire_Markup_Helper::ahref(
            	   strlen($router) ? $rg->href($router, $pager->list_action, 'page', 1, 'rows', $pager->rows_per_page) . $rg->query_string($conditions) : $rg->href($pager->list_action, 'page', 1, 'rows', $pager->rows_per_page) . $rg->query_string($conditions),
            	   $lc->get('caption_for_first_page'),
            	   $lc->get('title_for_first_page'),
            	   '',
            	   $tabindex > 0 ? $tabindex++ : 0
            	)
        	);
        }
        
        if (($pager->current) > 1) {
            $_pager .= sprintf('<li id="goto_previous">%s</li>',
        	   Fire_Markup_Helper::ahref(
            	   strlen($router) ? $rg->href($router, $pager->list_action, 'page', $pager->current - 1, 'rows', $pager->rows_per_page) . $rg->query_string($conditions) : $rg->href($pager->list_action, 'page', $pager->current - 1, 'rows', $pager->rows_per_page) . $rg->query_string($conditions),
            	   $lc->get('caption_for_previous_page'),
            	   $lc->get('title_for_previous_page'),
            	   '',
            	   $tabindex > 0 ? $tabindex++ : 0
        	   )
    	   );
        }
        
        while ($page = $pager->getPages()) {
            
            if ($page == $pager->current) {
            	$_pager .= '<li id="current_page">';
            	$_pager .= $lc->san(sprintf('[ %d ]', $pager->current));
            	$_pager .= '</li>';
            } else {
                $_pager .= sprintf('<li>%s</li>',
                    Fire_Markup_Helper::ahref(
                        strlen($router) ? $rg->href($router, $pager->list_action, 'page', $page, 'rows', $pager->rows_per_page).$rg->query_string($conditions) : $rg->href($pager->list_action, 'page', $page, 'rows', $pager->rows_per_page).$rg->query_string($conditions),
                        $lc->get('caption_for_goto_page', $page),
                        $lc->get('title_for_goto_page', $page),
                        '',
                        $tabindex > 0 ? $tabindex++ : 0
            	   )
            	);
            }
        }
        
        if (($pager->current) < $pager->last) {
            $_pager .= sprintf('<li id="goto_next">%s</li>',
        	   Fire_Markup_Helper::ahref(
            	   strlen($router) ? $rg->href($router, $pager->list_action, 'page', $pager->current + 1, 'rows', $pager->rows_per_page).$rg->query_string($conditions) : $rg->href($pager->list_action, 'page', $pager->current + 1, 'rows', $pager->rows_per_page).$rg->query_string($conditions),
            	   $lc->get('caption_for_next_page'),
            	   $lc->get('title_for_next_page'),
            	   '',
            	   $tabindex > 0 ? $tabindex++ : 0
        	   )
        	);
        }
        
        if ($pager->current < $pager->last) {
            $_pager .= sprintf('<li id="goto_last">%s</li>',
        	   Fire_Markup_Helper::ahref(
            	   strlen($router) ? $rg->href($router, $pager->list_action, 'page', $pager->last, 'rows', $pager->rows_per_page).$rg->query_string($conditions) : $rg->href($pager->list_action, 'page', $pager->last, 'rows', $pager->rows_per_page).$rg->query_string($conditions),
            	   $lc->get('caption_for_last_page'),
            	   $lc->get('title_for_last_page'),
            	   '',
            	   $tabindex > 0 ? $tabindex++ : 0
        	   )
        	);
        }
        
        $_pager .= !$_simple ? "</ul>\n" : '';
        
        $_show_rows = explode(',', $show_rows);
        
        if (!strlen($show_rows) || (count($_show_rows) < 1)) {
        	return $_pager;
        }
        
        $_rows = !$_simple ? '<ul id="rows_per_page">' : '';
        while (list(, $row) = each($_show_rows)) {
            $row = trim($row);
            if ($row == $pager->rows_per_page) {
            	$_rows .= '<li id="current_rows">';
            	$_rows .= $lc->get('caption_for_showing_rows_per_page', $row);
            	$_rows .= '</li>';
            } else {
                $_rows .= sprintf('<li id="show_rows_%d">%s</li>',
                    $row,
                    Fire_Markup_Helper::ahref(
                        strlen($router) ? $rg->href($router, $pager->list_action, 'page', $pager->current, 'rows', $row).$rg->query_string($conditions) : $rg->href($pager->list_action, 'page', $pager->current, 'rows', $row).$rg->query_string($conditions),
                        $lc->get('caption_for_show_rows_per_page', $row),
                        $lc->get('title_for_show_rows_per_page', $row),
                        '',
                        $tabindex > 0 ? $tabindex++ : 0
                    )
            	);
            }
        }
        $_rows .= !$_simple ? "</ul>\n" : '';
        
        return $_pager . $_rows;
    }
}
?>