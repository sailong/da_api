<?php
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '/home/www/dzbwvip/source/class/class_core.php';
$discuz = & discuz_core::instance();
$discuz->init();


$list=DB::query("select uid,resideprovince,cron_fensi_state from ".DB::table("common_member_profile")." where cron_fensi_state=0 order by uid asc limit 1 ");
while($user = DB::fetch($list) )
{

	if($user['resideprovince'])
	{
		$tongcheng=DB::query("select uid from ".DB::table("common_member_profile")." where resideprovince='".$user['resideprovince']."' order by uid desc ");
		while($row = DB::fetch($tongcheng) )
		{
			$aaa=DB::fetch_first("select id from jishigou_buddys where uid='".$user['uid']."' and buddyid='".$row['uid']."' ");
			if(empty($aaa))
			{
				$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$user['uid']."','".$row['uid']."','1','','".time()."','','".time()."') ");

				//echo "insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$user['uid']."','".$row['uid']."','1','','".time()."','','".time()."') <hr>";

			}

			$bbb=DB::fetch_first("select id from jishigou_buddys where uid='".$row['uid']."' and buddyid='".$user['uid']."' ");
			if(empty($bbb))
			{
				$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$row['uid']."','".$user['uid']."','1','','".time()."','','".time()."') ");
				//echo "insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$user['uid']."','".$row['uid']."','1','','".time()."','','".time()."') <hr>";
			}
		}
		DB::query("UPDATE ".DB::table('common_member_profile')."  SET cron_fensi_state=1 WHERE uid='".$user['uid']."' "); 
	}

}

?>
