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
	
	$ecname=getecprefix();
	//$getstat['usrnickname'];
 $navtitle=$getstat['usrnickname'].'的产品管理'; 
$op=$_GET['op'];
$gid=$_GET['gid'];
   
	$pagesize = 21;
		$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);
	

	$count = DB::result_first("select count(*) from ".$ecname."goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 and  uid=$uid");
	$shoplist = array();
	if($count) {
		$query = DB::query("select goods_id,goods_name,goods_thumb,uid from  ".$ecname."goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 and  uid=$uid order by goods_id desc limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['goods_name'] =  cutstr($row['goods_name'], '8', '...');
			$shoplist[] = $row;
		}
	}

 	$multi = multi($count, $pagesize, $page, "home.php?mod=spacecp&ac=shop");
 
 
$starttime = $_SGLOBAL['timestamp'];

if($op=='del'){
	
	if($gid){DB::query("update ".$ecname."goods set  is_delete=1 WHERE goods_id=$gid");
	//echo "update ".$ecname."goods set  is_delete=1 WHERE goods_id=$gid";exit;
	 
	showmessage('已删除成功', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
	}
	
}

 
$templates = 'home/spacecp_shop';
include_once template($templates);

 
//连接字符
function simplode($ids) {
	return "'".implode("','", $ids)."'";
}

//取得扩展属性
function getatt($id) {
	$ecname=getecprefix();
	$count = DB::result_first("SELECT count(1) FROM ".$ecname."goods_attr AS g  LEFT JOIN ".$ecname."attribute AS a  ON a.attr_id = g.attr_id  WHERE goods_id = '$id' AND a.attr_type = 1 ");
	return $count;
}
?>