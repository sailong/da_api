<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_doing.php 19158 2010-12-20 08:21:50Z zhengqingpeng $
 */

 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
 
space_merge($space, 'count');
$act=$_GET['act'];

if($act=='iphone4s'){
    $srccode='source/group/iphone4s.php'; 
    include($srccode); 
    $templates='home/'.$gropid.'_iphone4s';
}

if($act=='qpg'){
    //��ƻ��Ԥ��ҳ��
    $ry=DB::query("SELECT tel FROM pre_lianghao WHERE flag=0 limit 12");
    while($relist=DB::fetch($ry)){
        $arr[]=$relist;
    }
    $countnum=count($arr);
    //var_dump($arr);
    $templates='home/20_qpg_iphone4s';
}
if($act=='qpgtc'){
    //��ƻ���ײ�ҳ��
    $templates='home/20_4s_taocan';
}
if($act=='qpgld'){
    //��ƻ���Ƚ�ҳ��
    $templates='home/20_4s_bijiao';
}
if($act=='qpgindex'){
    //��ƻ����ҳҳ��
    $templates='home/20_4s_index';
}
if($act=='weibo'){
$wburl=$_GET['wburl'];
$wburl=base64_decode($wburl);
$templates='home/'.$gropid.'_weibo';
}


 $act=$_POST['act'];
 if(!$act){
include_once(template($templates));}

?>