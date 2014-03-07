<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];


//广告接口 新
if($ac=="ad")
{
	$field_uid=$_G['gp_field_uid'];
	$page=$_G['gp_page'];
	$apptype=$_G['gp_apptype'];
	
	if($apptype)
	{
		$apptype_sql=" and (ad_apptype='".$apptype."' or ad_apptype='all' )";
	}
	else
	{
		$apptype_sql=" and (ad_apptype='".$apptype."' or ad_apptype='all' )";
	}

	if($page)
	{
		$sql=" and ad_page='".$page."' ";
	}
	
	$ad=DB::query("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height,ad_action,ad_action_id,ad_action_text from tbl_ad where ad_app='bwvip_app' ".$sql."  ".$apptype_sql."  ");
	while($row=DB::fetch($ad))
	{
		$arr=explode("|",$row['ad_url']);
		if(count($arr)>1)
		{
			$row['ad_action']=$arr[0];
			$row['ad_action_id']=$arr[1];
			$row['ad_action_text']=$arr[2];
			$row['ad_action_ext']=$arr[2];
			$row['event_url']=$arr[3];
		}
		else
		{
			$row['ad_action_ext']=$row['ad_action_text'];
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
		
		
		
		
		
		
		if($row['ad_action']!='apply_ticket_detail')
		{
			$row['ad_action_id']=51;
			if($row['ad_action_id'])
			{
			
				$row2 = DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where event_id='".$row['ad_action_id']."' and ad_page='ticket' order by ad_sort desc limit 1");
				$arr=explode("|",$row2['ad_url']);
				if(count($arr)>1)
				{
					$row2['ad_action']=$arr[0];
					$row2['ad_action_id']=$arr[1];
					$row2['ad_action_text']=$arr[2];
					$row2['event_url']=$arr[3];
				}
				if($row2['ad_file'])
				{
					$row2['ad_file']="".$site_url."/".$row2['ad_file'];
				}
				if($row2['ad_file_iphone4'])
				{
					$row2['ad_file_iphone4']="".$site_url."/".$row2['ad_file_iphone4'];
				}
				if($row2['ad_file_iphone5'])
				{
					$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
				}
				
				
			}
			
			
		}
		
		$row['apply_ad_list']=array_default_value($row2,array('event_url'));
		
		$list_data[]=array_default_value($row,array('event_url','apply_ad_list'));

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
	$apptype=$_G['gp_apptype'];
	if($apptype)
	{
		$apptype_sql=" and (ad_apptype='".$apptype."' or ad_apptype='all' )";
	}
	else
	{
		$apptype_sql=" and ( ad_apptype='all' )";
	}
	
	$list=DB::query("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height,ad_action,ad_action_id,ad_action_text from tbl_ad where ad_page='".$type."' and ad_app='bwvip_app'   ".$apptype_sql."  order by ad_sort desc ");

	while($ad = DB::fetch($list))
	{
		$arr=explode("|",$ad['ad_url']);
		if(count($arr)>1)
		{
			$ad['ad_action']=$arr[0];
			$ad['ad_action_id']=$arr[1];
			$ad['ad_action_text']=$arr[2];
			$ad['ad_action_ext']=$arr[2];
			$ad['event_url']=$arr[3];
			if(!$ad['event_url'])
			{
				$ad['event_url']=null;
			}
		}
		else
		{
			$row['ad_action_ext']=$ad['ad_action_text'];
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
		
		if($ad['ad_action']=='apply_ticket_detail')
		{
			if($ad['ad_action_id'])
			{
			
				$row2 = DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where event_id='".$ad['ad_action_id']."' and ad_page='ticket' order by ad_sort desc limit 1");
				$arr=explode("|",$row2['ad_url']);
				if(count($arr)>1)
				{
					$row2['ad_action']=$arr[0];
					$row2['ad_action_id']=$arr[1];
					$row2['ad_action_text']=$arr[2];
					$row2['event_url']=$arr[3];
				}
				if($row2['ad_file'])
				{
					$row2['ad_file']="".$site_url."/".$row2['ad_file'];
				}
				if($row2['ad_file_iphone4'])
				{
					$row2['ad_file_iphone4']="".$site_url."/".$row2['ad_file_iphone4'];
				}
				if($row2['ad_file_iphone5'])
				{
					$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
				}
				
				
			}
			
			
		}
		$ad['apply_ad_list']=array_default_value($row2,array('event_url'));
		
		$list_data[]=array_default_value($ad,array('event_url','apply_ad_list'));
	}
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);


}




/*以下是旧接口*/



//广告接口
if($ac=="ad_index")
{
	$apptype=$_G['gp_apptype'];
	
	if($apptype)
	{
		$apptype_sql=" and (ad_apptype='".$apptype."' or ad_apptype='all' )";
	}
	else
	{
		$apptype_sql=" and ( ad_apptype='all' )";
	}
	
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height,ad_action,ad_action_id,ad_action_text from tbl_ad where ad_page='ad_index' and ad_app='bwvip_app'  ".$apptype_sql."  order by rand() limit 1  ");
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
	$apptype=$_G['gp_apptype'];
	
	if($apptype)
	{
		$apptype_sql=" and (ad_apptype='".$apptype."' or ad_apptype='all' )";
	}
	else
	{
		$apptype_sql=" and ( ad_apptype='all' )";
	}
	
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height,ad_action,ad_action_id,ad_action_text from tbl_ad where ad_page='ad_welcome' and ad_app='bwvip_app'  ".$apptype_sql."  order by ad_sort desc limit 1  ");

	$arr=explode("|",$ad['ad_url']);
	if(count($arr)>1)
	{
		$ad['ad_action']=$arr[0];
		$ad['ad_action_id']=$arr[1];
		$ad['ad_action_text']=$arr[2];
		$ad['ad_action_ext']=$arr[2];
		$ad['event_url']=$arr[3];
		if(!$ad['event_url'])
		{
			$ad['event_url']=null;
		}
	}
	else
	{
		$ad['ad_action_ext']=$ad['ad_action_text'];
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