<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_space.php 22839 2011-05-25 08:05:18Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = empty($_GET['uid']) ? 0 : intval($_GET['uid']);


if($_GET['username']) {
	$member = DB::fetch_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[username]' LIMIT 1");
	if(empty($member)) {
		showmessage('space_does_not_exist');
	}
	$uid = $member['uid'];
}

$dos = array('index', 'doing', 'blog', 'album', 'friend', 'wall',
	'notice', 'share', 'home', 'pm', 'videophoto', 'favorite',
	'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'profile', 'plugin', 'spacesleft', 'left');

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';

if(in_array($do, array('home', 'doing', 'blog', 'album', 'share', 'wall'))) {
	if(!$_G['setting']['homestatus']) {
		showmessage('home_status_off');
	}
} else {
	$_G['mnid'] = 'mn_common';
}

if(empty($uid) || in_array($do, array('notice', 'pm'))) $uid = $_G['uid'];

if($uid) {
	$space = getspace($uid);
	if(empty($space)) {
		showmessage('space_does_not_exist');
	}
}

if(empty($space)) {
	if(in_array($do, array('doing', 'blog', 'album', 'share', 'home', 'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'group'))) {
		$_GET['view'] = 'all';
		$space['uid'] = 0;
	} else {
		showmessage('login_before_enter_home', null, array(), array('showmsg' => true, 'login' => 1));
	}
} else {

	$navtitle = $space['username'];

	if($space['status'] == -1 && $_G['adminid'] != 1) {
		showmessage('space_has_been_locked');
	}

	if(in_array($space['groupid'], array(4, 5, 6)) && ($_G['adminid'] != 1 && $space['uid'] != $_G['uid'])) {
		$_GET['do'] = $do = 'profile';
	}

	if($do != 'profile' && $do != 'index' && !ckprivacy($do, 'view')) {
		$_G['privacy'] = 1;
		require_once libfile('space/profile', 'include');
		include template('home/space_privacy');
		exit();
	}

	if(!$space['self'] && $_GET['view'] != 'eccredit') $_GET['view'] = 'me';

	get_my_userapp();

	get_my_app();
}

$diymode = 0;


if($space['groupid'] < 20) {
	//资料
	$info = DB::fetch_first("select residecity, company, bio from ".DB::table('common_member_profile')." where uid='$uid' limit 1");

	//勋章
	$medals = DB::fetch_first("select medals from ".DB::table('common_member_field_forum')." where uid='$uid' limit 1");
	$arr = explode('	', $medals['medals']);
	foreach($arr as $val) {
		$query = DB::query("select image, name from ".DB::table('forum_medal')." where medalid='$val' limit 1");
		while($row = mysql_fetch_assoc($query)) {
			$medal[] = $row;
		}
	}

	//参加的话题
	$query = DB::query("select tag from jishigou_tag_favorite where uid='$uid' order by dateline desc limit 4");
	while ($row = mysql_fetch_assoc($query)) {
		$topic[] = $row;
	}

	//关注
	$query = DB::query("select b.remark, m.uid, m.username from jishigou_buddys as b left join jishigou_members as m on b.buddyid=m.uid where b.uid='$uid' limit 4");
	while($row = mysql_fetch_assoc($query)) {
		$buddys[] = $row;
	}

	//粉丝
	$query = DB::query("select b.remark, m.uid, m.username from jishigou_buddys as b left join jishigou_members as m on b.uid=m.uid where b.buddyid='$uid' limit 4");
	while($row = mysql_fetch_assoc($query)) {
		$fans[] = $row;
	}

	//访客
	$query = DB::query("select uid, vuid, vusername from ".DB::table('home_visitor')." where uid='$uid' order by dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$visit[] = $row;
	}

	//群组
	$query = DB::query("select f.fid, f.name, ff.icon from ".DB::table('forum_groupuser')." g left join ".DB::table('forum_forum')." f on f.fid=g.fid left join ".DB::table("forum_forumfield")." ff on ff.fid=f.fid where g.uid='$uid' limit 0, 9");
	while($group = mysql_fetch_assoc($query)) {
		$group['icon'] = get_groupimg($group['icon'], 'icon');
		$grouplist[] = $group;
	}

	//标签
	$query = DB::query("select tag_name from jishigou_user_tag_fields where uid='$uid' limit 8");
	while($row = mysql_fetch_assoc($query)) {
		$tags[] = $row;
	}

	//参加的活动
	$query = DB::query("select e.title, e.image from jishigou_event_member as m left join jishigou_event as e on e.id=m.id where m.play=1 and m.fid='$uid'");
	while($row = mysql_fetch_assoc($query)) {
		$event[] = $row;
	}

	//相册
	$query = DB::query("select albumid, albumname, pic, picnum from ".DB::table('home_album')." where uid='$uid' order by updatetime desc limit 8");
	while($row = mysql_fetch_assoc($query)) {
		$pic[] = $row;
	}

	//动态
	$query = DB::query("SELECT * FROM ".DB::table('home_feed')." USE INDEX(hot) WHERE dateline>='$hotstarttime' ORDER BY hot DESC LIMIT 0,10");
	while ($value = DB::fetch($query)) {
		$hotlist[$value['feedid']] = $value;
	}
	/*
	//微博
	$query = DB::query("select username, content,  from jishigou_topic where uid='$uid' order by  desc");
	while ($value = DB::fetch($query)) {
		$hotlist[$value['feedid']] = $value;
	}
	*/
}


$row = DB::fetch_first("select id from jishigou_buddys where uid='".$_G['uid']."' and buddyid='$uid' limit 1");
if($row) {
	$guanzhu['buddy'] = '1';
} else {
	$guanzhu['buddy'] = '0';
}


$seccodecheck = $_G['setting']['seccodestatus'] & 4;
$secqaacheck = $_G['setting']['secqaa']['status'] & 2;

require_once libfile('space/'.$do, 'include');

?>