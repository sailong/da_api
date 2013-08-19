<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];


//广告接口 新
if($ac=="ad")
{
	$page=$_G['gp_page'];
	$field_uid=$_G['gp_field_uid'];
	if($page)
	{
		$sql=" and ad_page='".$page."' ";
	}
	
	$ad=DB::query("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_app='bwvip_app' ".$sql."    ");
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








//广告接口 欢迎页
if($ac=="ad_list")
{
	$type=$_G['gp_type'];
	
	$list=DB::query("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='".$type."' and ad_app='bwvip_app' order by ad_sort desc ");

	while($ad = DB::fetch($list))
	{
		$arr=explode("|",$ad['ad_url']);
		if(count($arr)>1)
		{
			$ad['ad_action']=$arr[0];
			$ad['ad_action_id']=$arr[1];
			$ad['ad_action_text']=$arr[2];
			$ad['event_url']=$arr[3];
			if(!$ad['event_url'])
			{
				$ad['event_url']=null;
			}
		}
		else
		{
			$ad['ad_action']="";
			$ad['ad_action_id']="";
			$ad['ad_action_text']="";
			$ad['event_url']=null;
		}

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
		$list_data[]=$ad;
	}
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);


}




/*以下是旧接口*/



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
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='ad_welcome' and ad_app='bwvip_app' order by ad_sort desc limit 1  ");

	$arr=explode("|",$ad['ad_url']);
	if(count($arr)>1)
	{
		$ad['ad_action']=$arr[0];
		$ad['ad_action_id']=$arr[1];
		$ad['ad_action_text']=$arr[2];
		$ad['event_url']=$arr[3];
		if(!$ad['event_url'])
		{
			$ad['event_url']=null;
		}
	}
	else
	{
		$ad['ad_action']="";
		$ad['ad_action_id']="";
		$ad['ad_action_text']="";
		$ad['event_url']=null;
	}

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