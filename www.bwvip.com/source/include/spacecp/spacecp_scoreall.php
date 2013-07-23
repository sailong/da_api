<?php
$id = $_GET['id'];
$uid = $_G['uid'];

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
for($i = 1; $i <= 21; $i++) {
	$num[] = $i;
}
$parscore = DB::fetch_first("select cs.id, cs.uid, cs.sais_id, cs.fuid, cs.tee, cs.par, cs.dateline, cf.fieldname from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid where cs.uid='".$uid."' and cs.id='$id'");
$query = DB::query("select cs.id, cs.uid, cs.score, cs.pars, cs.total_score, cs.status, cmp.realname from ".DB::table('common_score')." as cs left join ".DB::table('common_member_profile')." as cmp on cmp.uid=cs.uid where cs.sais_id='".$parscore['sais_id']."' and cs.fuid='".$parscore['fuid']."' and tee='".$parscore['tee']."' and dateline='".$parscore['dateline']."' order by cs.total_score asc");
while($row = DB::fetch($query)) {
	//$row['realname'] = (strlen($row['realname']) < 5) ? $row['realname'] : mb_substr($row['realname'], 0, 5, 'utf-8');
	$groupscore[] = $row;
}
$parscore['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
$array = array('par', 'score', 'pars');
foreach($parscore as $key=>$val) {
	if(in_array($key, $array)) {
		$parscore[$key] = explode('|', $val);
	}
}
$m = 0;
foreach($groupscore as $key=>$val) {
	foreach($val as $k=>$v) {
		if(in_array($k, $array) && !empty($v)) {
			$groupscore[$key][$k] = explode('|', $v);
		}
	}
	if($val['status']) {
		$m++;
	}
}
$i = 0;
$j = 0;
foreach($groupscore as $key=>$val) {
	if($val['status']) {
		$arr[$i] = $groupscore[$key];
		$i++;
	} else {
		$noscore[$j] = $groupscore[$key];
		$j++;
	}
}
$count = count($arr);
foreach($noscore as $k=>$v) {
	$arr[$count] = $noscore[$k];
	$count++;
}
/*
$arr = scorerank($groupscore);
function scorerank($arr) {
	$count = count($arr);
	$t = '';
	$aa = '';
	$i = 0;
	if(empty($arr['0']['status'])) {
		$t = $arr['0'];
		unset($arr['0']);
		$arr[$count] = $t;
		foreach($arr as $k=>$v) {
			$aa[$i] = $arr[$k];
			$i++;
		}
		unset($arr);
		$arr = $aa;
		scorerank($arr);
	} else {
		return $arr;
	}
}
*/
include template('home/spacecp_scoreall');
?>