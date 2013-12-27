<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('caddie', 'drill', 'expert')) ? trim($_GET['op']) : 'caddie';
$do = $_GET['do'];
$c = $_GET['c'];
$uid = $_G['uid'];
$id = $_GET['id'];


$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($row = DB::fetch($query)) {
	$area[] = $row;
}
for($i = 1; $i <= 11; $i++) {
	$year[] = $i <= 10 ? $i.'年' : '10年以上';
}
if($operation == 'caddie') {
	$where = $id ? 'ha.id='.$id : '1';
	$apply = DB::fetch_first('select ha.*, cd.name, cf.fieldname from '.DB::table('home_apply')." as ha left join ".DB::table('common_district')." as cd on cd.id=ha.provinceid left join ".DB::table('common_field')." as cf on cf.uid=ha.fuid where ha.uid='$uid' and applytype=0 and $where");
	if($apply) {
		$apply['lasttime'] = date('Y-m-d', $apply['lasttime']);
	}
	if($do == 'edit') {
		$query = DB::query("select id, uid, fieldname from ".DB::table('common_field')." where province=".$apply['provinceid']." order by id desc");
		while($row = DB::fetch($query)) {
			$field[] = $row;
		}
	} elseif($do == 'save') {
		$arr = $_POST;
		$arr['uid'] = $uid;
		$arr['lasttime'] = time();
		$arr['isverify'] = '0';
		$arr['applytype'] = '0';
		unset($arr['profilesubmitbtn']);
		if($c == 'up') {
			$id = $_POST['caddieid'];
			unset($arr['caddieid']);
			$row = DB::update('home_apply', $arr, array('id'=>$id, 'uid'=>$uid));
		} else {
			$arr['username'] = $_G['username'];
			$arr['applytime'] = time();
			$row = DB::insert('home_apply', $arr);
		}
		if($row) {
			showmessage('申请已提交，请耐心等待管理员的审核', 'home.php?mod=spacecp&ac=apply&op=caddie');
		} else {
			showmessage('申请失败，请重新填写信息', 'home.php?mod=spacecp&ac=apply&op=caddie');
		}
	}
} elseif($operation == 'drill') {
	$where = $id ? 'id='.$id : '1';
	$apply = DB::fetch_first('select * from '.DB::table('home_apply')." where uid='$uid' and applytype=1 and $where");
	if($apply) {
		$apply['lasttime'] = date('Y-m-d', $apply['lasttime']);
	}
	if($do == 'save') {
		$arr = $_POST;
		$arr['uid'] = $uid;
		$arr['lasttime'] = time();
		$arr['isverify'] = '0';
		$arr['applytype'] = '1';
		unset($arr['profilesubmitbtn']);
		if($c == 'up') {
			$id = $_POST['drillid'];
			unset($arr['drillid']);
			$row = DB::update('home_apply', $arr, array('id'=>$id, 'uid'=>$uid));
		} else {
			$arr['username'] = $_G['username'];
			$arr['applytime'] = time();
			$row = DB::insert('home_apply', $arr);
		}
		if($row) {
			showmessage('申请已提交，请耐心等待管理员的审核', 'home.php?mod=spacecp&ac=apply&op=drill');
		} else {
			showmessage('申请失败，请重新填写信息', 'home.php?mod=spacecp&ac=apply&op=drill');
		}
	}
} elseif($operation == 'expert') {
	$where = $id ? 'id='.$id : '1';
	$apply = DB::fetch_first('select * from '.DB::table('home_apply')." where uid='$uid' and applytype=2 and $where");
	if($apply) {
		$apply['lasttime'] = date('Y-m-d', $apply['lasttime']);
	}
	if($do == 'save') {
		$arr = $_POST;
		$arr['uid'] = $uid;
		$arr['lasttime'] = time();
		$arr['isverify'] = '0';
		$arr['applytype'] = '2';
		unset($arr['profilesubmitbtn']);
		if($c == 'up') {
			$id = $_POST['drillid'];
			unset($arr['drillid']);
			$row = DB::update('home_apply', $arr, array('id'=>$id, 'uid'=>$uid));
		} else {
			$arr['username'] = $_G['username'];
			$arr['applytime'] = time();
			$row = DB::insert('home_apply', $arr);
		}
		if($row) {
			showmessage('申请已提交，请耐心等待管理员的审核', 'home.php?mod=spacecp&ac=apply&op=expert');
		} else {
			showmessage('申请失败，请重新填写信息', 'home.php?mod=spacecp&ac=apply&op=expert');
		}
	}
}

if($_GET['do'] == 'area') {
	$id = $_GET['val'];
	$query = DB::query('select id, uid, fieldname from '.DB::table('common_field')." where province='".$id."' order by id desc");
	$option = "<option value='0'>请选择</option>";
	while($row = mysql_fetch_assoc($query)) {
		$option .= "<option value='".$row['uid']."'>".$row['fieldname']."</option>";
	}
	echo $option;
} else {
	$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
	$template = 'home/spacecp_'.$usergroup.'_apply';
	include template($template);
}
?>