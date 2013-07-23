<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home_spacecp.php 22021 2011-04-20 07:00:41Z congyushuai $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require_once libfile('function/spacecp');
require_once libfile('function/magic');


$ac = in_array($_GET['ac'], array('list', 'play')) ?  $_GET['ac'] : 'list';
$op = empty($_GET['op']) ? '' : $_GET['op'];
$_G['mnid'] = 'mn_N1b8e';

if(in_array($ac, array('privacy'))) {
	if(!$_G['setting']['homestatus']) {
		showmessage('home_status_off');
	}
}

$actives = array($ac => ' class="a"');

$seccodecheck = $_G['group']['seccode'] ? $_G['setting']['seccodestatus'] & 4 : 0;
$secqaacheck = $_G['group']['seccode'] ? $_G['setting']['secqaa']['status'] & 2 : 0;

$navtitle = lang('core', 'title_setup');

if(lang('core', 'title_memcp_'.$ac)) {
	$navtitle = lang('core', 'title_memcp_'.$ac);
}

require_once libfile('video/'.$ac, 'include');

?>