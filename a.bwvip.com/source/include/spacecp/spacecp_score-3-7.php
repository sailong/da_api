<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$pagesize = 2;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);


$operation = in_array($_GET['op'], array('addscore', 'listscore', 'edit', 'del', 'save', 'par', 'area')) ? trim($_GET['op']) : 'addscore';
$do = $_GET['do'];



//头部菜单的切换
if(in_array($operation, array('addscore', 'listscore', 'edit'))) {
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
//轮次
for($j = 1; $j <= 4; $j++) {
	$coun[] = $j;
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
$array = array('par', 'score', 'pars', 'avglength', 'shangdao', 'shangdaolv', 'greens', 'greenslv', 'avepushs', 'pushs', 'eagle', 'birdie', 'bunkerlv', 'bunker', 'avepush', 'furthest', 'bogi', 'doubles', 'penalty', 'evenpar', 'other');

if($operation == 'save') {
	$arr = $_POST;
	$arr['total'] = $arr['score']['20'];
	foreach($arr as $key=>$val) {
		if(in_array($key, $array)) {
			$arr[$key] = implode('|', $val);
		}
	}
	$arr['fieldtime'] = strtotime($arr['fieldtime']);
	$arr['uid'] = $_G['uid'];
	$arr['addtime'] = time();
	unset($arr['profilesubmitbtn']);

	$dir = 'uploadfile/score/'.date('Ym');
	if(!is_dir($dir)) {
		mkdir($dir, 0777);
	}
	chmod($dir, 0777);
	$tmp = $_FILES['uploadimg']['tmp_name'];
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for($i = 1; $i <= 8; $i++) {
		$max = strlen($str);
		$start = rand(0, $max);
		$rand .= substr($str, $start, '1');
	}
	$file = $dir.'/'.$_G['uid'].'_'.date('YmdHis').$rand.'.jpg';
	$info = move_uploaded_file($tmp, $file);
	$arr['uploadimg'] = $file;
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
	$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$_G['uid']."'"));
	if($count) {
		$query = DB::query('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='".$_G['uid']."' order by cs.id desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['addtime'] = date('Y-m-d H:i:s', $row['addtime']);
			$row['fieldtime'] = date('Y-m-d', $row['fieldtime']);
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

	$theurl = 'home.php?mod=spacecp&ac=score&op=list';
	$multi = multi($count, $pagesize, $page, $theurl);
} elseif($operation == 'edit') {
	$id = $_GET['id'];
	$scorelist = DB::fetch_first('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.id=$id and cs.uid='".$_G['uid']."'");

	$scorelist['fieldtime'] = date('Y-m-d', $scorelist['fieldtime']);
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
		showmessage('操作成功', 'home.php?mod=spacecp&ac=score&op=list');
	} else {
		showmessage('操作失败', 'home.php?mod=spacecp&ac=score&op=list');
	}
}



if($operation == 'par') {
	$id = $_GET['id'];
	$par = DB::fetch_first('select id, par from '.DB::table('common_field')." where id='$id' order by id desc limit 1");
	$arr = explode(',', $par['par']);
	$out = 0;
	$in = 0;
	for($i = 0; $i < 9; $i++) {
		$out = $out + $arr[$i];
	}
	for($i = 9; $i < 19; $i++) {
		$in = $in + $arr[$i];
	}
	$total = $out + $in;
	for($i = 0; $i <= 18; $i++) {
		if($i == '9') {
			$n = $n + 1;
			$pars['9'] = $out;
		}
		$pars[$n] = $arr[$i];
		$n++;
	}
	$pars['19'] = $in;
	$pars['20'] = $total;
	$str = implode('|', $pars);
	echo $str;
} elseif($operation == 'area') {
	$aid = $_GET['val'];
	$query = DB::query('select id, fieldname from '.DB::table('common_field')." where province='$aid' order by id desc");
	while($list = mysql_fetch_assoc($query)) {
		$field[] = $list;
	}
	$option = "<option value='0'>请选择</option>";
	foreach($field as $k=>$v) {
		$option .= "<option value='".$v['id']."'>".$v['fieldname']."</option>";
	}
	echo $option;
} else {
	$usergroup = $_G['groupid'];
	$template = 'home/spacecp_'.$usergroup.'_score';
	include template($template);
}
?>