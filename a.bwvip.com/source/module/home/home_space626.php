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


$zyuid = !empty($_GET['uid']) ? $_GET['uid'] : intval($_G['uid']);
//资料
$userinfo = DB::fetch_first("select m.username, m.credits, mp.realname, mp.resideprovince, mp.residecity, mp.company, mp.site, mp.bio, mc.views, jm.fans_count, jm.follow_count, jm.topic_count, jm.validate from ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as mp on mp.uid=m.uid left join ".DB::table('common_member_count')." as mc on mc.uid=m.uid left join jishigou_members as jm on jm.uid=m.uid where m.uid='$zyuid' order by m.uid desc");

if($_GET['username']) {
	$member = DB::fetch_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='$_GET[username]' LIMIT 1");
	if(empty($member)) {
		showmessage('space_does_not_exist');
	}
	$uid = $member['uid'];
}

$dos = array('index', 'doing', 'blog', 'album', 'friend', 'wall',
	'notice', 'share', 'home', 'pm', 'videophoto', 'favorite',
	'thread', 'trade', 'poll', 'activity', 'debate', 'reward', 'profile', 'plugin', 'common', 'action', 'saishiinfo', 'video', 'member','ershou','mingpian', 'rank' , 'mxlist','weiyuan','shop');
	//添加action

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

$seccodecheck = $_G['setting']['seccodestatus'] & 4;
$secqaacheck = $_G['setting']['secqaa']['status'] & 2;

//add by xgw on 2012年3月14日
$bgset=DB::query("SELECT bgcolor,headerpic,bgpic FROM pre_qiye_logo WHERE uid=".$uid);
$bgsetarr=DB::fetch($bgset);
//echo "SELECT bgcolor,headerpic,bgpic FROM pre_qiye_logo WHERE uid=".$_G["uid"];
//var_dump($bgsetarr);
//add end



/*修改弹窗状态2012/5/8*/
if($_G['gp_update_status']=='window')
{
    if($_G['gp_bugletgx_id']){
      DB::update('buglet_gx',array('is_window'=>1),array('id'=>$_G['gp_bugletgx_id']));exit;
	}
}



/*获取小喇叭的信息·2012/5/8*/
$trumpet_msg = DB::fetch_first(" SELECT  bgx.*,bmsg.*  FROM ".DB::table('buglet_gx')." as bgx LEFT JOIN ".DB::table('buglet_msg')." as bmsg ON bmsg.buglet_id = bgx.msgid where bgx.touid ='".$_G['uid']."' and bgx.is_window = 0 ");
$is_buglet_msg = !empty($trumpet_msg) ? 1 : 0;






$visitname = $space['self'] ? '我' : 'Ta ';



#require_once libfile('space/'.$do, 'include');#/home/www/dzbwvip/source/include/space/space_common.php

require_once('/home/www/dzbwvip/source/include/space/space_common626.php');
?>