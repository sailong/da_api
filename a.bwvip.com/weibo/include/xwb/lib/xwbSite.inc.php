<?php 
/*******************************************************************
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename xwbSite.inc.php $
 *
 * @Author 鐙愮嫺<foxis@qq.com> $
 *
 * @Date 2010-12-06 04:58:24 $
 *******************************************************************/ 

if( !defined('IS_IN_XWB_PLUGIN') ){
	exit('Access Denied!');
}


function xwb_setSiteUserLogin($uid)
{
    $uid = (int) $uid;
    if ($uid < 1) 
    {
    	return false;
    }
	
	
	Load::functions('member');
	$member = jsg_member_login_set_status($uid);
   	
   	
   	$GLOBALS['jsg_sys_config']['login_user'] = $member;

    return true;
}

function xwb_setSiteRegister($name, $nickname, $email, $pwd=false)
{
    $db = XWB_plugin::getDB();

    $uid = 0;
    $username = $name;
    $password = ($pwd ? $pwd : rand(100000,999999));

	Load::functions('member');
	$uid = jsg_member_register($username, $password, $email, $nickname);

    $rst = array('uid'=>$uid, 'password'=>$password);

    return $rst;
}
