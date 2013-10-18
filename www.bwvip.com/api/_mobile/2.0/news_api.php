<?php
/*
*
* bwvip.com
* 新闻相关
*
*/
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}



$ac=$_G['gp_ac'];

//page 1
$page=$_G['gp_page'];
if(!$page)
{
	$page=1;
}
$page_size=$_G['gp_page_size'];
if(!$page_size)
{
	$page_size=10;
}
if($page==1)
{
	$page_start=0;
}
else
{
	$page_start=($page-1)*($page_size);
}
//page 2
$page2=$_G['gp_page'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size'];
if(!$page_size2)
{
	$page_size2=9;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}

$root_path = dirname(dirname(dirname(dirname(__FILE__))));



//分享统计
if($ac == 'share_count')
{
	if(strpos($_SERVER['HTTP_USER_AGENT'],"iPhone"))
	{
		$agent="iPhone";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPad"))
	{
		$agent="iPad";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iPod"))
	{
		$agent="iPod";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"iOS"))
	{
		$agent="iOS";
	}
	else if(strpos($_SERVER['HTTP_USER_AGENT'],"Android"))
	{
		$agent="Android";
	}
	else
	{
		$agent='other';
	}

	$type=$_G['gp_type'];
	$arc_id=$_G['gp_arc_id'];
	$uid=$_G['gp_uid'];
	$ip = get_real_ip();
	if($type && $arc_id)
	{
		$rs=DB::query("update tbl_arc set ".$type."=".$type."+1 where arc_id='".$arc_id."'  ");
		$now_time = time();
		DB::query("insert into tbl_share_log(uid,arc_id,type,agent,ip,addtime) values('{$uid}','{$arc_id}','{$type}','{$agent}','{$ip}','{$now_time}')");
		api_json_result(1,0,'分享成功',$data);
	}
	else
	{
		api_json_result(1,0,'参数不完整',$data);
	}
	
}

?>