<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_upload.php 22318 2011-04-29 09:34:15Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once './source/class/class_core.php';
//设置标题
$uid = !empty($_GET['uid']) ? $_GET['uid'] : $_G['uid'];

$getstat = array();
	$getstat = getusrarry($uid);
	//$getstat['usrnickname'];
 $navtitle=$getstat['usrnickname'].'的产品管理'; 
$op=$_GET['op'];
$gid=$_GET['gid'];
   
	$pagesize = 21;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	if($page < 1) {
		$page = 1;
	}
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);

	$count = DB::result_first("select count(*) from daz_shop.dz_goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 and  uid=$uid");
	$shoplist = array();
	if($count) {
		$query = DB::query("select goods_id,goods_name,goods_thumb,uid from  daz_shop.dz_goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 and  uid=$uid order by goods_id desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['goods_name'] =  cutstr($row['goods_name'], '8', '...');
			$shoplist[] = $row;
		}
	}

 	$multi = multi($count, $pagesize, $page, "home.php?mod=spacecp&ac=shop");
 
 
$starttime = $_SGLOBAL['timestamp'];

if($op=='del'){
	
	if($gid){DB::query("update daz_shop.dz_goods set  is_delete=1 WHERE goods_id=$gid");
	//echo "update daz_shop.dz_goods set  is_delete=1 WHERE goods_id=$gid";exit;
	 
	showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
	}
	
}

 
$templates = 'home/spacecp_shop';
include_once template($templates);

 
//连接字符
function simplode($ids) {
	return "'".implode("','", $ids)."'";
}
?>