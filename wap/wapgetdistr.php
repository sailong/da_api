<?php
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
$id = $_POST['val'];
	$query = DB::query("select id, name from ".DB::table('common_district')." where upid=".$id);
	$option = "<option value='0'>请选择</option>";
	while($row = mysql_fetch_assoc($query)) {
		$option .= "<option value='".$row['name']."'>".$row['name']."</option>";
	}
	echo $option;
?>