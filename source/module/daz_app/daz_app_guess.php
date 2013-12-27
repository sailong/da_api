<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: daz_app_guess.php 22839 2012/3/5 08:05:18Z angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$uid = empty($_G['uid']) ? 0 : intval($_G['uid']);

$dos = array('index','view_guess_tag');

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))?$_GET['do']:'index';


require_once libfile('daz_app/'.$do, 'include');





?>