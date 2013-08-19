<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */

define('APPTYPEID', 1);
define('CURSCRIPT', 'home');
$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$is_exist_amp = is_int(strpos($url_this,'|'));
//var_dump($is_exist_amp);die;
if($is_exist_amp) {
	$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$url_this = str_replace("|","&",$url_this);
	header("Location:{$url_this}");exit;
}

if(!empty($_GET['mod']) && ($_GET['mod'] == 'misc' || $_GET['mod'] == 'invite')) {
	define('ALLOWGUEST', 1);
}
if($_GET['test'] == 1) {
    echo 'test';
}
require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance();

$cachelist = array('magic','userapp','usergroups', 'diytemplatenamehome');
$discuz->cachelist = $cachelist;
$discuz->init();

$space = array();

$mod = getgpc('mod');
if(!in_array($mod, array('space', 'spacecp', 'misc', 'magic', 'editor', 'invite', 'task', 'medal', 'rss','news'))) {
	$mod = 'news';
	$_GET['do'] = 'index';
}

if($mod == 'space' && ((empty($_GET['do']) || $_GET['do'] == 'index') && ($_G['inajax'] || !$_G['setting']['homestatus']))) {
	$_GET['do'] = 'profile';
}

define('CURMODULE', $mod);

runhooks();
if($_GET['test'] == 1) {
    var_dump(libfile('wap/'.$mod, 'module'));
}
require_once libfile('wap/'.$mod, 'module');


?>