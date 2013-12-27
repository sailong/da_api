<?php
define('IN_DISCUZ', true); 
 
require_once 'source/class/class_core.php'; 


$uid = !empty($_GET['uid']) ? $_GET['uid'] : $_G['uid'];
$cachelist = array();
$discuz = & discuz_core::instance();
$discuz->init();
 $act=$_POST['act'];
 $compid=$_POST['compid'];
$uid=$_G['uid'];
//$username=$_POST['username'];
$mobile=$_POST['mobile'];

$fctbox=$_POST['fctbox'];
$brbox=$_POST['brbox'];
$spbox=$_POST['spbox'];



$chejiahao=$_POST['chejiahao'];
$autoname=$_POST['autoname'];
$autoplate=$_POST['autoplate'];
$license=$_POST['license'];
 $query = DB::query('SELECT * FROM zdy_common_auto where level=1 order by firstletter' );
   while($value = DB::fetch($query)) {
			$carlst[] = $value;
		}	
 
$operation = $_GET['operation'] ? $_GET['operation'] : 'add';
 
 if($act=='add')
	 {  	 
	 if($spbox>0)
{$autoid=$spbox;}
else
{
	if($brbox>0)
	{$autoid=$brbox;}
	else
	{
		if($fctbox>0)
		{$autoid=$fctbox;}
	}
}
if($autoid==0)
{
	exit("请选择汽车类型");
	}
		//插入数据表zdy_getauto 
		//$insert = "INSERT INTO zdy_getauto (compid,uid,mobile,autoid,chejiahao,autoname,autoplate,license) VALUES ('$compid','$uid','$mobile','$autoid','$chejiahao','$autoname','$autoplate','$license');";
		 //DB::query($insert); 	  
		// echo "已添加成功！";
		//header("Location:iphone4s.php?operation=list"); 
}  
 ?>
