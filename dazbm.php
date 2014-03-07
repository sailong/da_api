<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */


define('APPTYPEID', 1);
define('CURSCRIPT', 'home');

if(!empty($_GET['mod']) && ($_GET['mod'] == 'misc' || $_GET['mod'] == 'invite')) {
	define('ALLOWGUEST', 1);
}



require_once './source/class/class_core.php';
require_once './source/function/function_home.php';

$discuz = & discuz_core::instance();
$discuz->init();



$hot_2013district=array(
                 'tj511'=>'5/11天津',
                 'gz524'=>'5/24广州',
                 'sz531'=>'5/31深圳',
                 'hz615'=>'6/15杭州',
                 'sh621'=>'6/21上海',
                 'cs629'=>'6/29长沙',
                 'bj719'=>'7/19北京',
                 'dl726'=>'7/26大连',
                 'zz89'=>'8/9郑州',
                 'cd824'=>'8/24成都',
                 'sz830'=>'8/30苏州',
                 'fz97'=>'9/7福州',);
$mod = !in_array($discuz->var['mod'], $modarray) ? 'dazbm' : $discuz->var['mod'];
define('CURMODULE', $mod);

require libfile('function/member');
require libfile('class/member');
require DISCUZ_ROOT.'./source/module/member/member_'.$mod.'.php';





?>