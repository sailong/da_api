<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_register.php 20126 2011-02-16 02:42:26Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);
$ctl_obj = new register_ctl();
$ctl_obj->setting = $_G['setting'];
$ctl_obj->template = 'member/register';

/*针对天津搞协报分 走的报名入口 取出对应协会中的球队*/
if($_G['gp_sid']){
    $query = DB::query("select team_id,team_name from ".DB::table('golf_team')." where  sid=".$_G['gp_sid']);
    while($result = DB::fetch($query)) {
         $ctl_obj->golf_team[] = $result;
    }
}

$ctl_obj->on_register();
?>