<?php
/*
*
* bwvip.com
* 视频
*
*/
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}

//当前文件所在路径
$current_path = dirname(__FILE__); 


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





//视频列表
if($ac == 'video_list')
{
	
	$total=DB::result_first("select count(video_id) from tbl_video where 1 ".$sql." ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
	
		$list=DB::query("select video_id,video_name,video_pic,video_url,video_content,video_sort,video_addtime from tbl_video where 1 ".$sql." order by video_addtime desc limit $page_start,$page_size ");
		while($row=DB::fetch($list))
		{
			
			if($row['video_pic'])
			{
				$row['video_pic']=$site_url."/".$row['video_pic'];
				$row['video_pic_info']=getimagesize($row['video_pic']);
			}
			else
			{
				$row['video_pic_info']=null;
			}
			
			$list_data[]=array_default_value($row,array('video_pic_info'));
			
		}
	}
	
	$data['title']		= "data";
	$data['data']     =  $list_data;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
}




//视频详细
if($ac == 'video_detail')
{

	$video_id=$_G['gp_video_id'];
	$pic_width=$_G['gp_pic_width'];
	

	
	if($video_id)
	{
		
		$video_info=DB::fetch_first("select video_id,video_name,video_pic,video_url,video_content,video_sort,video_addtime from tbl_video where video_id='".$video_id."' limit 1 ");
		
		$video_info['video_addtime']=date("Y-m-d",$video_info['video_addtime']);
		if($video_info['video_content'])
		{
			$video_info['video_content']=str_replace("src=\"/Public/editor/attached/image","src=\"".$site_url."/Public/editor/attached/image",$video_info['video_content']);
		}
		if($pic_width)
		{
			$video_info['video_content']=str_replace("<img","<img style=\"width:".$pic_width."px; margin:0 auto;\" ",$video_info['video_content']);
		}
		
		if($video_info['video_pic'])
		{
			$video_info['video_pic']=$site_url."/".$video_info['video_pic'];
			$video_info['video_pic_info']=getimagesize($video_info['video_pic']);
		}
		else
		{
			$video_info['video_pic_info']=null;
		}
		

		
		$data['title']	= "data";
		$data['data']   =  array_default_value($video_info,array('video_pic_info'));
		api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
	}
	else
	{
		api_json_result(1,1,"没有找到该视频",$data);
	}

	
}



?>