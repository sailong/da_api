<?php
if(!defined('IN_DISCUZ'))
{
    exit('Access Denied');
}

$language=$_G['gp_language'];
if(!$language)
{
	$language='cn';
	$language_sql=" and language='cn' ";
}
else
{
	$language_sql=" and language='".$language."' ";
}


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


$ac=$_G['gp_ac'];
//球场新闻 arc_type=Q
if($ac=="news_list")
{

	$language_sql="";

	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_type='Q' and field_uid>0  ". $language_sql);
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime, '%Y%m%d') as today from tbl_arc where  arc_model='arc' and arc_state=1 and arc_type='Q' and field_uid>0  $language_sql order by today desc,arc_sort desc,arc_addtime desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			$row = array_default_value($row);
			//$row = check_field_to_relace($row, array('replynum'=>'0'));
			$list_data[]=$row;
			$i++;
		}

			
	}
	
    if(empty($list_data))
	{
        $list_data = null;
    }
	$data['title']="list_data";
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//客户端列表 推荐首页下
if($ac=="app_tuijian_list")
{

	$keyword=$_G['gp_keyword'];
	if($keyword!="")
	{
		$where .=" and field_name like '%".$keyword."%' ";
	}
	
	$source=$_G['gp_source'];
	if(!empty($source))
	{
		if($source=='sina'){
			$where .=" and (field_uid>0 or field_uid=0) and field_uid!=3807135 ";
		}else{
			$where .=" and (field_uid>0 or field_uid=0) ";
		}
	}
	else
	{
		$where .=" and field_uid>0 ";
	}

	$list=DB::query("select field_id,field_uid,field_name,field_pic,field_addtime,field_content,field_download_num,field_zuobiao from tbl_field where field_status=1 ".$where." order by field_addtime desc ");
	$i=0;
	while($row = DB::fetch($list))
	{
		
		if($row['field_pic'])
		{
			$row['field_pic']="".$site_url."/".$row['field_pic'];
		}
		if($row['field_addtime'])
		{
			$row['field_addtime']=date("Y-m-d G:i:s",$row['field_addtime']);
		}
		
		$row = array_default_value($row);
		$list_data[]=$row;
	}
	
	$data['title']="list_data";
	$data['data']=array(
		'is_search'=>1,
		'app_list'=>$list_data,
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}




//客户端列表   带搜索 分页
if($ac=="app_list")
{
	$app_version_type=$_G['gp_app_version_type'];
	$page = $_G['gp_page'];
	if(!$app_version_type)
	{
		$app_version_type='android';
	}
	
	$keyword=$_G['gp_keyword'];
	if($keyword!="")
	{
		$where =" and field_name like '%".$keyword."%' ";
	}
	$source=$_G['gp_source'];
	if(!empty($source))
	{
		if($source=='sina'){
			$where .=" and (field_uid>0 or field_uid=0) and field_uid!=3807135 ";
		}else{
			$where .=" and (field_uid>0 or field_uid=0) ";
		}
	}
	else
	{
		$where .=" and field_uid>0 ";
	}
	
	$total=DB::result_first(" select count(field_id) from tbl_field where field_status=1 ".$where." order by field_addtime asc ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select field_id,field_uid,field_name,field_pic,field_addtime,field_content,field_download_num,field_zuobiao from tbl_field where field_status=1 ".$where." order by field_addtime asc limit $page_start,$page_size ");
		$i=0;
		while($row = DB::fetch($list))
		{
			
			if($row['field_pic'])
			{
				$row['field_pic']="".$site_url."/".$row['field_pic'];
			}
			if($row['field_addtime'])
			{
				$row['field_addtime']=date("Y-m-d G:i:s",$row['field_addtime']);
			}
			
			//下载信息
			$download_info=DB::fetch_first("select app_version_id,app_version_type,app_version_number,app_version_name,app_version_content,app_version_file,app_version_url,app_version_language,app_version_size,app_version_addtime,app_version_package from tbl_app_version where app_version_type='".$app_version_type."' and field_uid='".$row['field_uid']."' order by app_version_number desc limit 1 ");
			
			if($download_info['app_version_addtime'])
			{
				$row['app_version_addtime']=date("Y-m-d",$download_info['app_version_addtime']);
			}
			
			if($download_info['app_version_language']=='cn')
			{
				$row['app_version_language']="中文版";
			}
			else if($download_info['app_version_language']=='en')
			{
				$row['app_version_language']="英文版";
			}
			else
			{
				
			}
			
			$row['app_version_id']=$download_info['app_version_id'];
			$row['app_version_size']=$download_info['app_version_size'];
			$row['app_version_name']=$download_info['app_version_name'];
			$row['app_download_url']=$download_info['app_version_file'];
			$row['app_version_package']=$download_info['app_version_package'];
			$row['app_version_number']=$download_info['app_version_number'];
			
			unset($download_info);
			
			$row = array_default_value($row);
			$list_data[]=$row;
		}
	}
	
	$data['title']="list_data";
	$data['data']=array(
		'is_search'=>1,
		'app_list'=>$list_data,
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}




//客户端详细页
if($ac=="app_detail")
{
	$field_uid=$_G['gp_field_uid'];
	$app_version_type=$_G['gp_app_version_type'];
	if(!$app_version_type)
	{
		$app_version_type='android';
	}
	
	
	$field_app_info=DB::fetch_first("select field_id,field_uid,field_name,field_pic,field_addtime,field_content,field_download_num,field_zuobiao from tbl_field where field_uid='".$field_uid."'  ");
	if($field_app_info['field_id'])
	{
		if($field_app_info['field_pic'])
		{
			$field_app_info['field_pic']="".$site_url."/".$field_app_info['field_pic'];
		}
		if($field_app_info['field_addtime'])
		{
			$field_app_info['field_addtime']=date("Y-m-d",$field_app_info['field_addtime']);
		}
		
		//图片
		$pic_list=DB::query("select field_pic_name,field_pic_url from tbl_field_pic where field_uid='".$field_uid."' ");
		while($row=DB::fetch($pic_list))
		{
			if($row['field_pic_url'])
			{
				$row['field_pic_url']="".$site_url."/".$row['field_pic_url'];
			}
			$field_app_info['pic_list'][]=array_default_value($row);
		}
		
		
		
		//下载信息
		$download_info=DB::fetch_first("select app_version_id,app_version_type,app_version_number,app_version_name,app_version_content,app_version_file,app_version_url,app_version_language,app_version_size,app_version_addtime,app_version_package from tbl_app_version where app_version_type='".$app_version_type."' and field_uid='".$field_uid."' order by app_version_number desc limit 1 ");
		if($download_info['app_version_addtime'])
		{
			$field_app_info['app_version_addtime']=date("Y-m-d",$download_info['app_version_addtime']);
		}
		
		if($download_info['app_version_language']=='cn')
		{
			$field_app_info['app_version_language']="中文版";
		}
		else if($download_info['app_version_language']=='en')
		{
			$field_app_info['app_version_language']="英文版";
		}
		else
		{
			
		}
		
		$field_app_info['app_version_id']=$download_info['app_version_id'];
		$field_app_info['app_version_size']=$download_info['app_version_size'];
		$field_app_info['app_version_name']=$download_info['app_version_name'];
		$field_app_info['app_download_url']=$download_info['app_version_file'];
		$field_app_info['app_version_package']=$download_info['app_version_package'];
		$field_app_info['app_version_number']=$download_info['app_version_number'];
		
		unset($download_info);
	
		
		//download_info
		if($field_app_info['field_uid']==1186)
		{
			$field_app_info['ios_info']="LakeMalarenApp://com.lakemalaren.com";
		}
		else if($field_app_info['field_uid']==1160)
		{
			$field_app_info['ios_info']="NanShanApp://com.bwvip.NanShan";
		}
		else if($field_app_info['field_uid']==3803491)
		{
			$field_app_info['ios_info']="HuaBinAPP://com.lpga.com";
		}
		else if($field_app_info['field_uid']==1203)
		{
			$field_app_info['ios_info']="TianJinApp://com.tjbhsl.com";
		}
		else if($field_app_info['field_uid']==3807135)
		{
			$field_app_info['ios_info']="sinaApp://com.sina.com";
		}
		else
		{
			$field_app_info['ios_info']="";
		}
		
		
		$field_app_info=array_default_value($field_app_info,array('pic_list'));
		
		
		$data['title']="detail_data";
		$data['data']=$field_app_info;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	
	}
	else
	{
		api_json_result(1,1,"该应用不存在",null);
	}
	
	
	

}


//客户端下载统计
if($ac=="app_download")
{
	$field_id=$_G['gp_field_id'];
	$app_version_type=$_G['gp_app_version_type'];
	if($field_id)
	{
		$up=DB::query("update tbl_field set field_download_num=field_download_num+1 where field_id='".$field_id."' ");
		api_json_result(1,0,"统计成功",null);
	}
	else
	{
		api_json_result(1,1,"参数不完整",null);
	}
	
}
?>