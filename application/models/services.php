<?php
class Services extends Fire_Model {

    function getServices($page = 1, $itemsPerPage = ITEMS_PER_PAGE) {
        return $this->findAll(array('s.id', 's.code', 's.name_bg', 's.name_en', 's.name_ru', 'o.name_bg as operator_name'), array(), $page, $itemsPerPage, array('id DESC'), array(), 'services s INNER JOIN operators o ON s.operator_id = o.id');
    }
}
?>