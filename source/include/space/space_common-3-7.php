
<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_wall.php 16680 2010-09-13 03:01:08Z wangjinbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$pagesize = ($option == 'score') ? '2' : '20';
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);


//轮次
for($j = 1; $j <= 4; $j++) {
	$coun[] = $j;
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


$option = $_GET['op'];

$uid = $_GET['uid'];
$id = $_GET['id'];

if($option == 'score') {
	if($id) {
		$list = DB::fetch_first('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.id=$id and cs.uid=$uid");

		$array = array('par', 'score', 'pars', 'avglength', 'shangdao', 'shangdaolv', 'greens', 'greenslv', 'avepushs', 'pushs', 'eagle', 'birdie', 'bunkerlv', 'bunker', 'avepush', 'furthest', 'bogi', 'doubles', 'penalty', 'evenpar', 'other');

		if(!empty($list)) {
			foreach($list as $key=>$val) {
				if(in_array($key, $array)) {
					$list[$key] = array();
					$list[$key] = explode('|', $val);
				}
			}
			$list['fieldtime'] = date('Y-m-d', $list['fieldtime']);
		}
	} else {
		$theurl = 'home.php?mod=space&do=common&op=score&uid='.$uid;

		$count = DB::result(DB::query('select count(*) from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid'"));
		if($count) {
			$query = DB::query('select cs.*, cf.fieldname, cd.name from '.DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.id=cs.fieldid left join ".DB::table('common_district')." as cd on cd.id=cs.province where cs.uid='$uid' order by cs.id desc limit $start, $pagesize");
			while($row = mysql_fetch_assoc($query)) {
				$row['fieldtime'] = date('Y-m-d', $row['fieldtime']);
				$scorelist[] = $row;
			}
		}

		$array = array('par', 'score', 'pars');
		foreach($scorelist as $key=>$val) {
			foreach($val as $k=>$v) {
				if(!empty($val[$k])) {
					if(in_array($k, $array)) {
						$scorelist[$key][$k] = explode('|', $v);
					}
				}
			}
		}
		$multi = multi($count, $pagesize, $page, $theurl);
	}
} elseif($option == 'visiter') {
	$theurl = 'home.php?mod=space&do=common&op=visiter&uid='.$uid;
	$count = DB::result(DB::query("select count(uid) from ".DB::table('home_visitor')." where uid='$uid' order by dateline desc"));
	if($count) {
		$query = DB::query("select uid, vuid, vusername from ".DB::table('home_visitor')." where uid='$uid' order by dateline desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$visitermore[] = $row;
		}
	}
	$multi = multi($count, $pagesize, $page, $theurl);
} elseif($option == 'medalmore') {
	$medals = DB::fetch_first("select medals from ".DB::table('common_member_field_forum')." where uid='$uid' limit 1");
	$arr = explode('	', $medals['medals']);
	foreach($arr as $val) {
		$query = DB::query("select image, name from ".DB::table('forum_medal')." where medalid='$val' limit 1");
		while($row = mysql_fetch_assoc($query)) {
			$medalmore[] = $row;
		}
	}
}
if($option == 'tag') {
	$tag = $_GET['tag'];
	if($tag) {
		$url = 'weibo/index.php?mod=search&code=usertag&usertag='.$tag;
	} else {
		$url = 'weibo/index.php?mod=user_tag';
	}
}
if($option == 'tagcode') {
	$code = $_GET['code'];
	$url = 'weibo/index.php?mod=tag&code='.$code;
}
if($option == 'qun') {
	$qid = $_GET['qid'];
	if($qid) {
		$url = 'weibo/index.php?mod=qun&qid='.$qid;
	} else {
		$url = 'weibo/index.php?mod=qun';
	}
}
if($option == 'event') {
	$eid = $_GET['eid'];
	if($eid) {
		$url = 'weibo/index.php?mod=event&code=detail&id='.$eid;
	} else {
		$url = 'weibo/index.php?mod=event&code=myevent&type=part&uid='.$uid;
	}
}
if($option == 'topic') {
	$tcode = $_GET['tcode'];
	if($tcode) {
		$url = 'weibo/index.php?mod=tag&code='.$tcode;
	} else {
		$url = 'weibo';
	}
}
if($option == 'refer') {
	$url = 'weibo/index.php?mod=topic&code=myat';
}
if($option == 'follow') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=follow';
}
if($option == 'fans') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=fans';
}
if($option == 'weibo') {
	$url = 'weibo';
}
space_merge($space, 'count');

$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
$templates='home/'.$usergroup.'_common';

include_once(template($templates));
?>