<?php
/**
 *
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('add', 'list', 'edit', 'del', 'save', 'par', 'area')) ? trim($_GET['op']) : 'add';

//头部菜单的切换
if(in_array($operation, array('add', 'list', 'edit'))) {
	if($operation == 'edit') {
		$opactives = array('list' =>'class=a');
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

$query = DB::query('SELECT * FROM '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
}


if($operation == 'save') {
	$do = $_GET['do'];
	$arr = $_POST;
	//需要进行组合字符串的数组
	$array = array('par', 'score', 'pars', 'avglength', 'shangdao', 'shangdaolv', 'greens', 'greenslv', 'avepushs', 'pushs', 'eagle', 'birdie', 'bunkerlv', 'bunker', 'avepush', 'furthest', 'bogi', 'doubles', 'penalty', 'evenpar', 'other');
	foreach($arr as $key=>$val) {
		if(in_array($key, $array)) {
			$arr[$key] = implode('|', $val);
		}
	}

	$arr['city'] = substr($arr['city'], '0', '-2');
	$arr['county'] = substr($arr['county'], '0', '-2');
	$arr['fieldtime'] = strtotime($arr['fieldtime']);
	$arr['uid'] = $_G['uid'];
	$arr['addtime'] = time();
	unset($arr['profilesubmitbtn']);

	if($arr) {
		if($do == 'add') {
			$row = DB::insert('common_score', $arr);
			$url = 'home.php?mod=spacecp&ac=score';
		} elseif($do == 'edit') {
			unset($arr['fieldid']);
			unset($arr['province']);
			unset($arr['city']);
			unset($arr['county']);
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
	} else {
		showmessage('请正确填写内容', $url);
	}
} elseif($operation == 'list') {
	$pagesize = 2;
	$pagesize = mob_perpage($pagesize);
	$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	if($page < 1) {
		$page = 1;
	}
	$start = ($page-1)*$pagesize;
	ckstart($start, $pagesize);
	$theurl = 'home.php?mod=spacecp&ac=score&op=list';

	$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as sco left join ".DB::table('common_field')." as fil on sco.fieldid=fil.id where uid='$_G[uid]'"));
	if($count) {
		$query = DB::query('select sco.*, fil.fieldname from '.DB::table('common_score')." as sco left join ".DB::table('common_field')." as fil on sco.fieldid=fil.id where uid='$_G[uid]' order by id desc limit $start, $pagesize");
		while($info = mysql_fetch_assoc($query)) {
			$list[] = $info;
		}
		$i = 0;
		foreach($list as $key=>$value) {
			$i++;
			$list[$key]['rank'] = $i;
			$list[$key]['addtime'] = date('Y-m-d H:i:s', $list[$key]['addtime']);
		}
	}
	$array = array('par', 'score', 'pars');
	foreach($list as $key=>$val) {
		$list[$key]['fieldtime'] = date('Y-m-d', $list[$key]['fieldtime']);
		if(empty($val['par'])) {
			for($i = 1; $i < 21; $i++) {
				$val['par'] .= '|';
			}
		}
		foreach($val as $k=>$v) {
			if(in_array($k, $array)) {
				$list[$key][$k] = explode('|', $v);
			}
		}
	}
	$multi = multi($count, $pagesize, $page, $theurl);
} elseif($operation == 'edit') {
	$id = $_GET['id'];
	$query = DB::query('select sco.*, fil.fieldname from '.DB::table('common_score')." as sco left join ".DB::table('common_field')." as fil on sco.fieldid=fil.id where sco.id=$id limit 1");
	$arr = mysql_fetch_assoc($query);

	$array = array('par', 'score', 'pars', 'avglength', 'shangdao', 'shangdaolv', 'greens', 'greenslv', 'avepushs', 'pushs', 'eagle', 'birdie', 'bunkerlv', 'bunker', 'avepush', 'furthest', 'bogi', 'doubles', 'penalty', 'evenpar', 'other');
	if(empty($arr['par'])) {
		for($i = 1; $i < 21; $i++) {
			$arr['par'] .= '|';
		}
	}
	function getarea($id) {
		$area = DB::fetch_first('select name from '.DB::table('common_district')." where id=$id limit 1");
		return $area['name'];
	}
	$arr['province'] = getarea($arr['province']);
	$arr['city'] = getarea($arr['city']);
	$arr['county'] = getarea($arr['county']);
	foreach($arr as $key=>$val) {
		$arr['time'] = date('Y-m-d', $arr['fieldtime']);
		if(in_array($key, $array)) {
			$arr[$key] = array();
			$arr[$key] = explode('|', $val);
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
} else {
	//在添加状态下循环的输入框数
	for($i = 1; $i <= 21; $i++) {
		$num[] = $i;
		$j = $i - 1;
		$par .= "<td><input type='text' id='par_$j' name='par[]' value='' readonly /></td>";
	}
}


if($operation == 'par') {
	$id = $_GET['id'];
	$par = DB::fetch_first('select id, par from '.DB::table('common_field_test_1')." where id='$id' order by id desc limit 1");
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
	$str = $_GET['val'];
	$ind = strripos($str, '|');
	$num = '';
	if($ind) {
		$id = substr($str, '0', $ind);
		$start = $ind + 1;
		$num = substr($str, $start);
	} else {
		$id = $str;
	}
	if($num <= '3') {
		if($id != '0') {
			$query = DB::query('SELECT * FROM '.DB::table('common_district')." where upid='$id'");
			while($list = DB::fetch($query)) {
				$city[] = $list;
			}
			$selname = $num == '1' ? 'city' : ($num == '2' ? 'county' : 'city');
			$selec = "<select name='$selname' style='width:100px; height:100%; border:none; border-right:1px solid #abcdef; font-size:13px' onchange='getarea(2, this.value)'><option value='0'>请选择</option>";
			foreach($city as $key=>$val) {
				$selec .= "<option value='".$val['id'].'|'.$val['level']."'>".$val['name']."</option>";
			}
			$selec .= "</select>&nbsp;&nbsp;";
		}
		$where = $num == '1' ? 'province' : ($num == '2' ? 'city' : ($num == '3') ? 'county' : 'province');
		$query = DB::query('select id, fieldname from '.DB::table('common_field_test_1')." where $where='".$id."' order by id desc");
		while($list = mysql_fetch_assoc($query)) {
			$field[] = $list;
		}
		$option = "<option value='0'>请选择</option>";
		foreach($field as $k=>$v) {
			$option .= "<option value='".$v['id']."'>".$v['fieldname']."</option>";
		}
		echo $selec.'||'.$option;
	}
	
} else {
	include template('home/spacecp_score');
}
?>