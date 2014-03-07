<?
/*
*
*	报名页面
*
*/
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();


//echo date("Y-m-d G:i:s","1366619650");

$event_id=$_G['gp_event_id'];
if($event_id)
{
	include("../wap/baoming_".$event_id.".php");	
}

?>
