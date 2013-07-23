<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-07-17 07:35:13Z angf $
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$defaultop = 'star_rm';
$operation = in_array($_GET['op'], array('star_rm', 'members_fenzu', 'm_fenzu_submit', 'event_fenzu_rule_list', 'del_rule', 'member_allot', 'start_nd')) ? trim($_GET['op']) : $defaultop;


if($operation =='star_rm'){
    if(submitcheck('submit_star_rm')) {
        $checking = stripos($_G['gp_rm_member'],',') ? $_G['gp_rm_member'] : false;  //$data['rm_member']
        if($checking){
            foreach(explode(',',$_G['gp_rm_member']) as $k=>$v){
                if($v && is_numeric($v)) $data['rm_member'].=$v.",";
            }
            $data['rm_member']=$data['rm_member'].'0';
        }
        if($checking==false){
            showmessage("数据格式有误请重新填写","home.php?mod=spacecp&ac=dz_space_recommend");
        }
        DB::update("common_member_profile",$data,array('uid'=>$_G['uid']));
        showmessage("推荐成功","home.php?mod=space&uid=".$_G['uid']);
    }
    $rm_member_str = DB::fetch_first("select rm_member from ".DB::table("common_member_profile")." where uid =".$_G['uid']);
    include template("home/spacecp_dz_rm");
}


?>