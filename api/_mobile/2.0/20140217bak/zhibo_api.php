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



//详细页
if($ac == 'event_zhibo_detail')
{
	/* $zhibo_id = $_G['gp_zhibo_id'];
	if(empty($zhibo_id)){
		api_json_result(1,1,'缺少参数',$data);
	} */
	
	$event_id = $_G['gp_event_id'];
	if(empty($event_id)){
		api_json_result(1,1,'缺少参数',$data);
	}
	
	
	//直播详情$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
	$zhibo_detail=DB::fetch_first("select zhibo_id,event_id,zhibo_name from tbl_zhibo where 1 and event_id={$event_id}");
	$zhibo_id = $zhibo_detail['zhibo_id'];
	
	//播音员详情
	//$byy_detail=DB::fetch_first("select * from tbl_boyinyuan where 1 and zhibo_id={$zhibo_id}");
	if($zhibo_id){
		$byy_list=DB::query("select byy_id,byy_name,byy_pic from tbl_boyinyuan where 1 and zhibo_id={$zhibo_id}");//,byy_source,byy_detail
	}
	
	
	/* if($zhibo_detail)
	{ */
	$event_detail=DB::fetch_first("select event_id,event_name,event_id as event_uid,event_content,event_url,event_type,event_logo,event_starttime,event_endtime,event_video_url,event_audio_url,event_city,field_uid,by_top_pic,event_audio_bg from tbl_event where 1 and event_id=".$event_id);
	if($event_detail)
	{
		if($event_detail['field_uid'] != '')
		{
			$field_info = DB::fetch_first("select field_name from tbl_field where 1 and field_uid=".$event_detail['field_uid']);
		}
		
		$zhibo_detail['field_name'] = $field_info['field_name']?$field_info['field_name']:'';
		$zhibo_detail['event_city'] = $event_detail['event_city']?$event_detail['event_city'] : '';
		$zhibo_detail['event_name'] = $event_detail['event_name']?$event_detail['event_name']:'';
		$zhibo_detail['event_url'] = $event_detail['event_url'] ? $event_detail['event_url'] : '';
		$zhibo_detail['event_type'] = $event_detail['event_type'] ? $event_detail['event_type'] : '';
		$zhibo_detail['uid'] = $event_detail['event_id'];
		$zhibo_detail['event_content']=msubstr(cutstr_html($event_detail['event_content']),0,30);
		
		if(date('m',$event_detail['event_starttime']) == date('m',$event_detail['event_endtime']) )
		{
			$zhibo_detail['event_content']=date('Y年m月d',$event_detail['event_starttime'])." ~ ".date('d日',$event_detail['event_endtime']);
		}
		else
		{
			$zhibo_detail['event_content']=date('Y年m月d日',$event_detail['event_starttime'])." ~ ".date('m月d日',$event_detail['event_endtime']);
		}
		
		
		if($zhibo_detail['event_audio_bg'])
		{
			$zhibo_detail['event_audio_bg']=$site_url."/".$zhibo_detail['event_audio_bg'];
		}
		
		
		$zhibo_detail['event_audio_url'] = $event_detail['event_audio_url']?$event_detail['event_audio_url'] : '';
		$zhibo_detail['event_video_url'] = $event_detail['event_video_url']?$event_detail['event_video_url'] : '';
		$zhibo_detail['event_logo'] = '';
		$zhibo_detail['event_logo_info'] = '';
		if($event_detail['event_logo'])
		{
			$zhibo_detail['event_logo'] = $site_url.'/'.$event_detail['event_logo'];
			$event_logo_info = getimagesize($zhibo_detail['event_logo']);
			$event_logo_info = $event_logo_info ? $event_logo_info : '';
			$zhibo_detail['event_logo_info'] = $event_logo_info;
		}
		$zhibo_detail['by_top_pic'] = '';
		$zhibo_detail['by_top_pic_info'] = '';
		if($event_detail['by_top_pic'])
		{
			$zhibo_detail['by_top_pic'] = $site_url.'/'.$event_detail['by_top_pic'];
			$by_top_pic_info = getimagesize($zhibo_detail['by_top_pic']);
			$by_top_pic_info = $by_top_pic_info ? $by_top_pic_info : '';
			$zhibo_detail['by_top_pic_info'] = $by_top_pic_info;
		}
	}
	else
	{
		$zhibo_detail = null;
	}
		
		
		/* $zhibo_detail['zhibo_pic_info'] = null;
		if($zhibo_detail['zhibo_pic'])
		{
			$zhibo_detail['zhibo_pic'] = $site_url.'/'.$zhibo_detail['zhibo_pic'];
			$zhibo_pic_info = getimagesize($zhibo_detail['by_top_pic']);
			$zhibo_pic_info = $zhibo_pic_info ? $zhibo_pic_info : null;
			$zhibo_detail['zhibo_pic_info'] = $zhibo_pic_info;
		} */
		
		/* $zhibo_detail['event_starttime'] = date('Y-m-d H:i:s',$event_detail['event_starttime']);
		$zhibo_detail['event_endtime'] = date('Y-m-d H:i:s',$event_detail['event_endtime']);
		$zhibo_detail['zhibo_addtime'] = date('Y-m-d H:i:s',$zhibo_detail['zhibo_addtime']); */
		
	/* }
	else
	{	
		$zhibo_detail = null;
	} */
	if($byy_list)
	{
	
		while($row = DB::fetch($byy_list))
		{
			$row['byy_pic'] = $site_url.'/'.$row['byy_pic'];
			$byy_pic_info = getimagesize($row['byy_pic']);
			$byy_pic_info = $byy_pic_info ? $byy_pic_info : null;
			$row['byy_pic_info'] = $byy_pic_info;
			//$row['byy_addtime'] = date('Y-m-d H:i:s',$row['byy_addtime']);
			$new_byy_list[] = $row;
		}
		unset($byy_list);
	}
	else
	{	
		$new_byy_list = null;
	}
	
	
	$data['title']	="data";
	$data['data'] =array('zhibo'=>$zhibo_detail,'byy'=>$new_byy_list);
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//播放页
if($ac == 'event_zhibo_play')
{

	$event_id = $_G['gp_event_id'];
	if(empty($event_id))
	{
		api_json_result(1,1,'缺少参数',$data);
	}
	

	$event_detail=DB::fetch_first("select event_id,event_name,event_id as event_uid,event_content,event_url,event_type,event_logo,event_starttime,event_endtime,event_video_url,event_audio_url,event_city,field_uid,by_top_pic,event_audio_bg from tbl_event where 1 and event_id=".$event_id);
	
	if($event_detail)
	{
		if($event_detail['field_uid'] != '')
		{
			$field_info = DB::fetch_first("select field_name from tbl_field where 1 and field_uid=".$event_detail['field_uid']);
		}
		
		$zhibo_detail['field_name'] = $field_info['field_name']?$field_info['field_name']:'';
		$zhibo_detail['event_city'] = $event_detail['event_city']?$event_detail['event_city'] : '';
		$zhibo_detail['event_name'] = $event_detail['event_name']?$event_detail['event_name']:'';
		$zhibo_detail['event_url'] = $event_detail['event_url'] ? $event_detail['event_url'] : '';
		$zhibo_detail['event_type'] = $event_detail['event_type'] ? $event_detail['event_type'] : '';
		$zhibo_detail['uid'] = $event_detail['event_id'];
		$zhibo_detail['event_content']=msubstr(cutstr_html($event_detail['event_content']),0,30);
		
		if(date('m',$event_detail['event_starttime']) == date('m',$event_detail['event_endtime']) )
		{
			$zhibo_detail['event_content']=date('Y年m月d',$event_detail['event_starttime'])." ~ ".date('d日',$event_detail['event_endtime']);
		}
		else
		{
			$zhibo_detail['event_content']=date('Y年m月d日',$event_detail['event_starttime'])." ~ ".date('m月d日',$event_detail['event_endtime']);
		}
		
		
		if($event_detail['event_audio_bg'])
		{
			$zhibo_detail['event_audio_bg']=$site_url."/".$event_detail['event_audio_bg'];
		}else{
			$zhibo_detail['event_audio_bg'] = '';
		}
		
		
		$zhibo_detail['event_audio_url'] = $event_detail['event_audio_url']?$event_detail['event_audio_url'] : '';
		$zhibo_detail['event_video_url'] = $event_detail['event_video_url']?$event_detail['event_video_url'] : '';
		$zhibo_detail['event_logo'] = '';
		$zhibo_detail['event_logo_info'] = '';
		if($event_detail['event_logo'])
		{
			$zhibo_detail['event_logo'] = $site_url.'/'.$event_detail['event_logo'];
			$event_logo_info = getimagesize($zhibo_detail['event_logo']);
			$event_logo_info = $event_logo_info ? $event_logo_info : '';
			$zhibo_detail['event_logo_info'] = $event_logo_info;
		}
		$zhibo_detail['by_top_pic'] = '';
		$zhibo_detail['by_top_pic_info'] = '';
		if($event_detail['by_top_pic'])
		{
			$zhibo_detail['by_top_pic'] = $site_url.'/'.$event_detail['by_top_pic'];
			$by_top_pic_info = getimagesize($zhibo_detail['by_top_pic']);
			$by_top_pic_info = $by_top_pic_info ? $by_top_pic_info : '';
			$zhibo_detail['by_top_pic_info'] = $by_top_pic_info;
		}
	}
	else
	{
		$zhibo_detail = null;
	}
	
	$data['title']	="data";
	$data['data'] =array('zhibo'=>$zhibo_detail);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


?>
