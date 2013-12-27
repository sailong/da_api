<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_videophoto.php 22572 2011-05-12 09:35:18Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
 $op = $_GET['act'];
 $goods_id = $_GET['goods_id'];
 $uid = $_GET['uid'];
 $cat_id = $_GET['cat_id'];
 $ecname=getecprefix();
	
 $sql="SELECT a.cat_id,a.cat_name,b.uid from ".$ecname."category a LEFT JOIN ".$ecname."goods b on a.cat_id=b.cat_id where  b.is_on_sale = 1 AND b.is_alone_sale = 1 AND b.is_delete = 0 and b.uid=$uid  GROUP BY a.cat_id";
 $query = DB::query($sql);
		while ($value = DB::fetch($query)) {
			$sortlist[] = $value;
		}
if($goods_id >0) {  
$count = DB::result_first("select count(*) FROM ".$ecname."goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 AND goods_id=$goods_id");
	if($count) {
		$wburl="/shop/goods.php?id=".$goods_id;
		$templates='home/'.$gropid.'_shopgoods';
 	
		if($goods_id)
		{ 
		 $sql="SELECT cat_id from ".$ecname."goods where goods_id=$goods_id"; 
		 $cat_id1 = DB::result_first($sql); 
		 $goods_name=DB::result_first("SELECT goods_name from ".$ecname."goods where goods_id=$goods_id"); 
		 $goods_name=cutstr($goods_name,25,'');
		 
		$sql="SELECT cat_name from ".$ecname."category where cat_id=$cat_id1"; 
		 $cat_name = DB::result_first($sql); 
		}
	   $getloc="<a href='home.php?mod=space&uid=$uid&do=shop&cat_id=$cat_id1'>$cat_name</a> >> <a href='home.php?mod=space&uid=$uid&do=shop&goods_id=$goods_id'>$goods_name</a>";
			
		 
	}else{ 
		showmessage('商品已下架或删除！', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
	}
 } else {	
 //购物车
  if($op=='cart'){
	 $wburl="/shop/flow.php?step=cart"; 
	 $templates='home/'.$gropid.'_shopgoods';
	 $getloc="购物车";
   }elseif($op=='order'){
	 $wburl="/shop/user.php?act=order_list";
	 $templates='home/'.$gropid.'_shopgoods';
	 $getloc="订单";
 	}else {
		 if($uid>0)
		 {
			 $strwhere=' and uid='.$uid;
			 }
		 if($cat_id>0)
		 {
			 $strwhere=$strwhere.' and cat_id='.$cat_id;
			 }	  
	$perpage = 20;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	if($page < 1) {
		$page = 1;
	}
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage); 
	$count = DB::result_first("select count(*) FROM ".$ecname."goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 $strwhere");
	
	$shoplist = array();
	if($count) {
		$query = DB::query("SELECT *  from ".$ecname."goods where  is_on_sale = 1 AND is_alone_sale = 1 AND is_delete = 0 $strwhere LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$shoplist[] = $value;
		}
	}
 	$multi = multi($count, $perpage, $page, "home.php?mod=space&do=shop&act=list&uid=$uid");	
		if($cat_id)
		{ $sql="SELECT cat_name from ".$ecname."category where cat_id=$cat_id"; 
		 $cat_name = DB::result_first($sql); 
		}
	$getloc="<a href='home.php?mod=space&uid=$uid&do=shop&cat_id=$cat_id'>$cat_name</a>";
	$templates='home/'.$gropid.'_shop';
	 }
 }
 

include_once(template($templates));    
?>