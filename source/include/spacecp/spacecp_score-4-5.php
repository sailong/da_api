<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$pagesize = 5;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);


$operation = in_array($_GET['op'], array('addscore', 'verifyscore', 'listscore', 'detail', 'save')) ? trim($_GET['op']) : 'addscore';
$do = $_GET['do'];



//头部菜单的切换
if(in_array($operation, array('addscore', 'verifyscore', 'listscore', 'detail'))) {
	if($operation == 'detail') {
		$opactives = array('listscore' =>'class=a');
	} else {
		$opactives = array($operation =>'class=a');
	}
}

//球洞
for($i = 1; $i <= 21; $i++) {
	if($i == '10') {
		$data[$i] = 'OUT';
	} elseif($i == '20') {
		$data[$i] = 'IN';
	} elseif($i == '21') {
		$data[$i] = 'Total';
	} elseif($i > 9) {
		$data[$i] = $i - 1;
	} else {
		$data[$i] = $i;
	}
}
//在添加状态下循环的输入框数
for($i = 1; $i <= 21; $i++) {
	$num[] = $i;
}

//需要进行组合字符串的数组
$array = array('par', 'score', 'pars');

if($operation == 'save') {
	$arr = $_POST;
	$arr['total_score'] = $arr['score']['20'];
	foreach($arr as $key=>$val) {
		if(in_array($key, $array)) {
			$arr[$key] = implode('|', $val);
		}
	}
	$arr['uid'] = $_G['uid'];
	$arr['status'] = '1';
	$arr['addtime'] = time();
	unset($arr['profilesubmitbtn']);

	if($_FILES['uploadimg']['tmp_name']) {
		$tmp = $_FILES['uploadimg']['tmp_name'];
		$file = uploadimg();
		move_uploaded_file($tmp, $file);
		$arr['uploadimg'] = $file;
	} else {
		$arr['uploadimg'] = '';
	}

	$row = DB::update('common_score', $arr, array('id'=>$arr['id'], 'uid'=>$arr['uid']));
	if($row) {
		showmessage('操作成功', 'home.php?mod=spacecp&ac=score');
	} else {
		showmessage('操作失败', 'home.php?mod=spacecp&ac=score');
	}
} elseif($operation == 'verifyscore') {
	$scorelist = getscore('1', $_G['uid'], $start, $pagesize, $array);
	$multi = getmulti('1', $_G['uid'], $pagesize, $page, $operation);
} elseif($operation == 'listscore') {
	$scorelist = getscore('2', $_G['uid'], $start, $pagesize, $array);
	$multi = getmulti('2', $_G['uid'], $pagesize, $page, $operation);
} elseif($operation == 'detail') {
	$id = $_GET['id'];
	$scorelist = DB::fetch_first('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.id=$id and cs.uid='".$_G['uid']."'");

	$scorelist['dateline'] = date('Y-m-d', $scorelist['dateline']);
	foreach($scorelist as $key=>$val) {
		if(in_array($key, $array)) {
			$scorelist[$key] = array();
			$scorelist[$key] = explode('|', $val);
			foreach($scorelist[$key] as $k=>$v) {
				if($scorelist[$key][$k] == '') {
					$scorelist[$key][$k] = '0';
				}
			}
		}
	}
} elseif($operation == 'addscore') {
	if($_GET['id']) {
		$scorelist = DB::fetch_first("select cs.*, cf.fieldname, cd.name from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.id='".$_GET['id']."' and cs.uid='".$_G['uid']."' and cs.status='0' order by cs.dateline asc");
		$scorelist['dateline'] = date('Y-m-d', $scorelist['dateline']);
		foreach($scorelist as $key=>$val) {
			if(in_array($key, $array)) {
				$scorelist[$key] = array();
				$scorelist[$key] = explode('|', $val);
			}
		}
	} else {
		$countscore = DB::result(DB::query("select count(*) from ".DB::table('common_score')." where status='0' and uid='".$_G['uid']."'"));
		$query = DB::query("select cs.*, cf.fieldname, cd.name from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$_G['uid']."' and cs.status='0' order by cs.dateline asc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['dateline'] = date('Y-m-d', $row['dateline']);
			$scorelist[] = $row;
		}
		foreach($scorelist as $key=>$val) {
			foreach($val as $k=>$v) {
				if(in_array($k, $array)) {
					$scorelist[$key][$k] = array();
					$scorelist[$key][$k] = explode('|', $v);
				}
			}
		}
		$theurl = 'home.php?mod=spacecp&ac=score&op='.$operation;
		$multi = multi($countscore, $pagesize, $page, $theurl);
	}
}

function getscore($type, $uid, $start, $pagesize, $array) {
	$query = DB::query("select cs.*, cf.fieldname, cd.name from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$uid."' and cs.status='".$type."' order by cs.dateline desc limit $start, $pagesize");
	while($row = DB::fetch($query)) {
		$row['addtime'] = date('Y-m-d H:i:s', $row['addtime']);
		$row['dateline'] = date('Y-m-d', $row['dateline']);
		$childname = DB::fetch_first("select realname from ".DB::table('common_member_profile')." where uid='".$row['flag']."'");
		$row['childname'] = $childname['realname'];
		$list[] = $row;
	}
	foreach($list as $key=>$val) {
		foreach($val as $k=>$v) {
			if(in_array($k, $array)) {
				$list[$key][$k] = explode('|', $v);
			}
		}
	}
	return $list;
}
function getmulti($type, $uid, $pagesize, $page, $operation) {
	$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$uid."' and status='".$type."'"));
	$theurl = 'home.php?mod=spacecp&ac=score&op='.$operation;
	$multi = multi($count, $pagesize, $page, $theurl);

	return $multi;
}
function uploadimg() {
	$dir = 'uploadfile/score/'.date('Ym');
	if(!is_dir($dir)) {
		mkdir($dir, 0777);
	}
	chmod($dir, 0777);
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for($i = 1; $i <= 20; $i++) {
		$max = strlen($str);
		$start = rand(0, $max);
		$rand .= substr($str, $start, '1');
	}
	$file = $dir.'/'.$rand.'.jpg';
	return $file;
}

$usergroup = $_G['groupid'];
if($usergroup < 20) {
	$template = 'home/spacecp_10_score';
} else {
	$template = 'home/spacecp_'.$usergroup.'_score';
}

include template($template);
?>