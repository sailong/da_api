<?php
/*
*
* ad_api.php
* by zhanglong 2013-05-21
* field app 广告相关
*
*/
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];

//广告
if($ac=="ad")
{
	$page=$_G['gp_page'];
	$field_uid=$_G['gp_field_uid'];
	if($page)
	{
		$sql=" and ad_page='".$page."' ";
	}
	

	$ad=DB::query("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_app='field_app' and field_uid='".$field_uid."' ".$sql."    ");
	while($row=DB::fetch($ad))
	{
		$arr=explode("|",$row['ad_url']);
		if(count($arr)>1)
		{
			$row['ad_action']=$arr[0];
			$row['ad_action_id']=$arr[1];
			$row['ad_action_text']=$arr[2];
			$row['event_url']=$arr[3];
		}
		else
		{
			$row['ad_action']="";
			$row['ad_action_id']="";
			$row['ad_action_text']="";
			$row['event_url']="";
		}
	
		if($row['ad_file'])
		{
			$row['ad_file']="".$site_url."/".$row['ad_file'];
		}
		if($row['ad_file_iphone4'])
		{
			$row['ad_file_iphone4']="".$site_url."/".$row['ad_file_iphone4'];
		}
		if($row['ad_file_iphone5'])
		{
			$row['ad_file_iphone5']="".$site_url."/".$row['ad_file_iphone5'];
		}
		$list_data[]=array_default_value($row);
	}
		$data['title']		= "data";
		$data['data']     =  $list_data;
	if(!empty($list_data))
	{
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}






?>