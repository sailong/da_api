<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_logging.php 20126 2011-02-16 02:42:26Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

if(!in_array($_G['gp_action'], array('login', 'logout'))) {
	showmessage('undefined_action');
}


//手机登录 jack add 1230
preg_match_all("/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$_G['gp_username'], $if_mobile);
if(!empty($if_mobile[0]))
{
	$sql = "select uid,mobile from pre_common_member_profile where mobile='".$_G['gp_username']."' order by uid desc";
	$rs=DB::fetch_first($sql);
	$sql = "select uid,username from pre_common_member where uid='".$rs['uid']."' order by uid desc";
	$rs=DB::fetch_first($sql);
	$_G['gp_username']=$rs['username'];
}
//end



$ctl_obj = new logging_ctl();
$ctl_obj->setting = $_G['setting'];
$method = 'on_'.$_G['gp_action'];
$ctl_obj->template = 'member/login';
$ctl_obj->$method();

?>