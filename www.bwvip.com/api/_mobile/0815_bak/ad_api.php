<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];

//广告接口
if($ac=="ad_index")
{
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='ad_index' and ad_app='bwvip_app' order by rand() limit 1  ");
	if($ad['ad_file'])
	{
		$ad['ad_file']=$site_url."/".$ad['ad_file'];
	}
	if($ad['ad_file_iphone4'])
	{
		$ad['ad_file_iphone4']=$site_url."/".$ad['ad_file_iphone4'];
	}
	if($ad['ad_file_iphone5'])
	{
		$ad['ad_file_iphone5']=$site_url."/".$ad['ad_file_iphone5'];
	}

	if(!empty($ad))
	{
		$data['title']		= "data";
		$data['data']     =  $ad;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}


//广告接口 欢迎页
if($ac=="ad_welcome")
{
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='ad_welcome' and ad_app='bwvip_app' order by ad_addtime desc limit 1  ");

	$arr=explode("|",$ad['ad_url']);
	if(count($arr)>1)
	{
		$ad['ad_action']=$arr[0];
		$ad['ad_action_id']=$arr[1];
		$ad['ad_action_text']=$arr[2];
		$ad['event_url']=$arr[3];
	}
	else
	{
		$ad['ad_action']="";
		$ad['ad_action_id']="";
		$ad['ad_action_text']="";
		$ad['event_url']="";
	}
	
	//print_r($ad);
	

	if($ad['ad_file'])
	{
		$ad['ad_file']=$site_url."/".$ad['ad_file'];
	}
	if($ad['ad_file_iphone4'])
	{
		$ad['ad_file_iphone4']=$site_url."/".$ad['ad_file_iphone4'];
	}
	if($ad['ad_file_iphone5'])
	{
		$ad['ad_file_iphone5']=$site_url."/".$ad['ad_file_iphone5'];
	}
	if(!empty($ad))
	{
		$data['title']		= "data";
		$data['data']     =  $ad;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}



?>