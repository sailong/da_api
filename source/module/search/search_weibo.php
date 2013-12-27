<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: search_video.php 22166 2012/3/17 00:03:44Z Angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$srchtxt =getgpc('srchtxt');

$keyword = isset($srchtxt) ? htmlspecialchars(trim($srchtxt)) : '';
include template('search/weibo');

?>