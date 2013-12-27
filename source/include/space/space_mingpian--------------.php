<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_videophoto.php 22572 2011-05-12 09:35:18Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if ($_GET['cz']=='add') {
$uid=$_GET['uid'];
$cid=$_GET['cid'];
$count = DB::result_first("select count(*) FROM ".DB::table('common_mingpian')." WHERE uid='$uid' and cid='$cid'");
if($count) {
showmessage("名片递过了","home.php?mod=space&mod=space&uid=$uid");	
}else{
DB::insert('common_mingpian', array('uid' => $uid,'cid' => $cid,'isok' => 0));
showmessage("递名片成功","home.php?mod=space&mod=space&uid=$uid");
}

}
?>