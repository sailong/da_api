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

$dos = array('downdetails','fieldspace');

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';

$is_exist_amp = is_int(strpos($url_this,'&amp;'));
if($is_exist_amp) {
	$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$url_this = str_replace("&amp;","&",$url_this);
	header("Location:{$url_this}");exit;
}
if($_GET['test'] == 1) {
	echo '<pre>'; 
	$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$is_exist = is_int(strpos($url_this,'&&'));
	var_dump($is_exist);
	$url_this=str_replace("","&amp;",$news_blog['message']);
	var_dump($_SERVER);
	echo $url_this;die;
    var_dump(libfile('wap/'.$do, 'include'));
}
require_once libfile('wap/'.$do, 'include');

?>