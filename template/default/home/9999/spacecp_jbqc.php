<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_index.php 22814 2011-05-24 05:42:54Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$gid=$_G['groupid'];   //组id  现在的是25

//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','search','del','add')) ? $_GET['op'] : 'list';    
var_dump($op);
if($op=='list'){
	
}elseif($op=='search'){
	echo "search";
}elseif($op=='del'){
	echo "del";
}elseif($op=='add'){
	echo "add";
	
}



include_once(template('home/spacecp_jbqc'));
?>