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


$operation = in_array($_GET['op'], array('addscore', 'verifyscore', 'listscore', 'edit', 'del', 'save', 'par', 'area')) ? trim($_GET['op']) : 'addscore';
$do = $_GET['do'];



//头部菜单的切换
if(in_array($operation, array('addscore', 'verifyscore', 'listscore', 'edit'))) {
	if($operation == 'edit') {
		$opactives = array('listscore' =>'class=a');
	} else {
		$opactives = array($operation =>'class=a');
	}
}

foreach($array as $key=>$val) {
	$par[] = $val;
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

//地区
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
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
	$arr['addtime'] = time();
	$arr['dateline'] = strtotime($arr['dateline']);
	unset($arr['profilesubmitbtn']);

	$dir = 'uploadfile/score/'.date('Ym');
	if(!is_dir($dir)) {
		mkdir($dir, 0777);
	}
	chmod($dir, 0777);
	$tmp = $_FILES['uploadimg']['tmp_name'];
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for($i = 1; $i <= 20; $i++) {
		$max = strlen($str);
		$start = rand(0, $max);
		$rand .= substr($str, $start, '1');
	}
	$file = $dir.'/'.$rand.'.jpg';
	$info = move_uploaded_file($tmp, $file);
	$arr['uploadimg'] = $info ? $file : ($_POST['scoreimg'] ? $_POST['scoreimg'] : '');
	unset($arr['scoreimg']);
	if($do == 'addscore') {
		$row = DB::insert('common_score', $arr);
		$url = 'home.php?mod=spacecp&ac=score';
	} elseif($do == 'edit') {
		$row = DB::update('common_score', $arr, array('id'=>$arr['id'], 'uid'=>$_G['uid']));
		$url = 'home.php?mod=spacecp&ac=score&op=list';
	} else {
		showmessage('非法操作', 'home.php?mod=spacecp&ac=score');
	}
	if($row) {
		showmessage('操作成功', $url);
	} else {
		showmessage('操作失败', $url);
	}
} elseif($operation == 'listscore') {
	$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$_G['uid']."'"));
	if($count) {
		$query = DB::query('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$_G['uid']."' order by cs.id desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['addtime'] = date('Y-m-d H:i:s', $row['addtime']);
			$row['dateline'] = date('Y-m-d', $row['dateline']);
			$list[] = $row;
		}
	}
	foreach($list as $key=>$val) {
		foreach($val as $k=>$v) {
			if(in_array($k, $array)) {
				$list[$key][$k] = explode('|', $v);
			}
		}
	}

	$theurl = 'home.php?mod=spacecp&ac=score&op=listscore';
	$multi = multi($count, $pagesize, $page, $theurl);
} elseif($operation == 'edit') {
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
} elseif($operation == 'del') {
	$id = $_GET['id'];
	$row = DB::query('delete from '.DB::table('common_score')." where id='$id'");
	if($row) {
		showmessage('操作成功', 'home.php?mod=spacecp&ac=score&op=listscore');
	} else {
		showmessage('操作失败', 'home.php?mod=spacecp&ac=score&op=listscore');
	}
}

$usergroup = $_G['groupid'];
if ($usergroup < 20) {
$template='home/spacecp_10_score';
} else {
$template = 'home/spacecp_'.$usergroup.'_score';
}
include template($template);
?>