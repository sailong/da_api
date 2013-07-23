<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('field')) ? trim($_GET['op']) : 'field';
$do = $_GET['do'];
$uid = $_G['uid'];
$id = $_GET['id'];
$c = $_GET['c'];

$fielduser = DB::fetch_first("select cf.id, cm.username, cmp.realname, cmp.resideprovince, cmp.residecity, cmp.field1, cf.province, cf.length, cf.par, cf.standardpar, cf.cup, cf.address, cd.name from ".DB::table('common_member')." as cm left join ".DB::table('common_member_profile')." as cmp on cmp.uid=cm.uid left join ".DB::table('common_field')." as cf on cf.uid=cm.uid left join ".DB::table('common_district')." as cd on cd.id=cf.province where cm.uid='$uid' order by cm.uid desc");
if($fielduser['par']) {
	$fielduser['par'] = explode(',', $fielduser['par']);
}

$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($row = DB::fetch($query)) {
	$area[] = $row;
}
for($i = 1; $i <= 18; $i++) {
	$parnum[] = $i;
}

if($do == 'save') {
	$arr = $_POST;
	unset($arr['profilesubmitbtn']);
	$arr['par'] = implode(',', $arr['par']);
	$arr['fieldname'] = $fielduser['field1'];
	$arr['uid'] = $uid;
	if(empty($c)) {
		$row = DB::insert('common_field', $arr);
	} else {
		$row = DB::update('common_field', $arr, array('uid'=>$uid));
	}
	if($row) {
		showmessage('操作成功', 'home.php?mod=spacecp&ac=field&op=field');
	} else {
		showmessage('操作失败', 'home.php?mod=spacecp&ac=field&op=field');
	}
}

$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
$template = 'home/spacecp_'.$usergroup.'_field';
include template($template);
?>