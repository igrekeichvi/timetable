<?php
if (!defined('FIRE_DEFAULT_ROUTE')) {
	define('FIRE_DEFAULT_ROUTE','m_login');
}

define('FIRE_APPLICATION_PATH', sprintf('%s%sapplication', dirname(__FILE__),  DIRECTORY_SEPARATOR));
define('FIRE_LIBRARY_PATH', sprintf('%1$s%2$svendor%2$sFire%2$s', dirname(__FILE__), DIRECTORY_SEPARATOR));

require_once(FIRE_LIBRARY_PATH . 'Controller.php');
require_once(FIRE_LIBRARY_PATH . 'Session.php');
require_once(FIRE_LIBRARY_PATH . 'Routers/Raw.php');
	
Fire_Session::init('b_timetable', 3600);
Fire_Front_Controller::process(new Fire_Routers_Raw());
?>