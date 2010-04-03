<?php
$routes['default'] = array(
    'file'  =>  'Default',
    'class' =>  'Default'
);

$routes['m_default'] = array(
    'file'  =>  'management/Default',
    'class' =>  'ManagementDefault'
);

$routes['m_login'] = array(
	'file'	=>	'management/Login',
	'class'	=>	'ManagementLogin'
);

$routes['m_categories'] = array(
	'file'	=>	'management/CategoriesList',
	'class'	=>	'ManagementCategoriesList'
);

$routes['m_category_add'] = array(
	'file'	=>	'management/CategoryNew',
	'class'	=>	'ManagementCategoryNew'
);
	

$routes['m_operator_add'] = $routes['m_operators'] = array(
	'file'  => 'management/OperatorNew',
	'class' =>  'ManagementOperatorNew'
);

$routes['m_operator_edit'] = array(
	'file'  => 'management/OperatorEdit',
    'class' => 'ManagementOperatorEdit'
);

$routes['m_operator_delete'] = array(
    'file'  => 'management/OperatorDelete',
    'class' => 'ManagementOperatorDelete'
);

$routes['m_services'] = $routes['m_service_add'] = array(
    'file'  => 'management/ServiceNew',
    'class' => 'ManagementServiceNew'
);

$routes['m_service_edit'] = array(
    'file'	=> 'management/ServiceEdit',
    'class' => 'ManagementServiceEdit'
);

$routes['m_service_delete'] = array(
    'file' 	=> 'management/ServiceDelete',
    'class' => 'ManagementServiceDelete'
);
?>