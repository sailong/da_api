<?php
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
$page2=$_G['gp_page2'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size2'];
if(!$page_size2)
{
	$page_size2=10;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}



//直播列表
if($ac=='zhibo_list')
{
	$list=DB::query("select zhibo_id,event_id,zhibo_name,zhibo_pic,zhibo_addtime from tbl_zhibo where 1 order by zhibo_addtime desc limit $page_start,$page_size");
	
	while($row = DB::fetch($list))
	{
		$row['zhibo_addtime'] = date('Y-m-d H:i:s',$row['zhibo_addtime']);
		$list_data[]=$row;
	}
	$data['title']	="data";
	$data['data'] =$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
}


//直播详情
if($ac=='zhibo_detail')
{
	$zhibo_id = $_G['gp_zhibo_id'];
	if(empty($zhibo_id)){
		api_json_result(1,1,'缺少参数',$data);
	}
	//直播详情$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
	$zhibo_detail=DB::fetch_first("select * from tbl_zhibo where 1 and zhibo_id={$zhibo_id}");
	//播音员详情
	$byy_detail=DB::fetch_first("select * from tbl_boyinyuan where 1 and zhibo_id={$zhibo_id}");
	
	if($zhibo_detail)
	{
		$event_detail=DB::fetch_first("select event_name,event_logo from tbl_event where 1 and event_id=".$zhibo_detail['event_id']);
		$zhibo_detail['event_name'] = $event_detail['event_name'];
		$zhibo_detail['event_logo'] = $site_url.'/'.$event_detail['event_logo'];
		
		$event_logo_info = getimagesize($zhibo_detail['event_logo']);
		$event_logo_info = $event_logo_info ? $event_logo_info : null;
		$zhibo_detail['event_logo_info'] = $event_logo_info;
		
		$zhibo_detail['zhibo_pic'] = $site_url.'/'.$zhibo_detail['zhibo_pic'];
		$zhibo_pic_info = getimagesize($zhibo_detail['zhibo_pic']);
		$zhibo_pic_info = $zhibo_pic_info ? $zhibo_pic_info : null;
		$zhibo_detail['zhibo_pic_info'] = $zhibo_pic_info;
		$zhibo_detail['zhibo_addtime'] = date('Y-m-d H:i:s',$zhibo_detail['zhibo_addtime']);
	}
	else
	{	
		$zhibo_detail = null;
	}
	if($byy_detail)
	{
		$byy_detail['byy_pic'] = $site_url.'/'.$byy_detail['byy_pic'];
		$byy_pic_info = getimagesize($byy_detail['byy_pic']);
		$byy_pic_info = $byy_pic_info ? $byy_pic_info : null;
		$byy_detail['byy_pic_info'] = $byy_pic_info;
		
		$byy_detail['byy_addtime'] = date('Y-m-d H:i:s',$byy_detail['byy_addtime']);
	}
	else
	{	
		$byy_detail = null;
	}
	
	
	$data['title']	="data";
	$data['data'] =array('zhibo'=>$zhibo_detail,'byy'=>$byy_detail);
	api_json_result(1,0,$app_error['event']['10502'],$data);
}

?>
