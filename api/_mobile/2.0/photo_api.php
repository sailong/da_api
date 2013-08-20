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





if($ac=="album_list")
{

	$total=DB::result_first("select count(album_id) from tbl_album  ");
	
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select album_id,album_name,album_sort,album_addtime from tbl_album where 1 order by album_addtime desc limit $page_start,$page_size  ");
		
		while($row = DB::fetch($list))
		{
			$photo=DB::result_first("select photo_url from tbl_photo where album_id='".$row['album_id']."' order by photo_addtime desc limit 1 ");
			if($photo)
			{
				$row['album_fenmian']=get_small_pic($site_url."/".$photo);
				$row['album_fenmian_info']=getimagesize($row['album_fenmian']);
			}
			else
			{
				$row['album_fenmian']="";
				$row['album_fenmian_info']=null;
			}
			$row['album_addtime']=date("Y-m-d",$row['album_addtime']);
			$list_data[]=array_default_value($row,array('album_fenmian_info'));
		}
	}
	
	
	$data['title']	="list_data";
	$data['data'] =$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
	
}




//photo_list

if($ac=="photo_list")
{
	$album_id=$_G['gp_album_id'];
	if($album_id)
	{
		$sql =" and album_id='".$album_id."' ";
	}
	
	$total=DB::result_first("select count(photo_id) from tbl_photo where 1 ".$sql." ");
	$max_page=intval($total/$page_size2);
	if($max_page<$total/$page_size2)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page2)
	{
		$list=DB::query("select photo_id,photo_name,photo_url,photo_addtime from tbl_photo where 1 ".$sql." order by photo_addtime asc limit $page_start2,$page_size2  ");
		while($row = DB::fetch($list))
		{
			
			if($row['photo_url'])
			{
				$row['photo_url']=$site_url."/".$row['photo_url'];
				$row['photo_url_info']=getimagesize($row['photo_url']);
				
				$row['photo_url_small']=get_small_pic($row['photo_url']);
				if(getimagesize($row['photo_url_small']))
				{	
					$row['photo_url_small']=get_small_pic($row['photo_url']);
					$row['photo_url_small_info']=getimagesize($row['photo_url_small']);
				}
				else
				{
					$row['photo_url_small']="";
					$row['photo_url_small_info']=null;
				}
				
			}
			else
			{
				$row['photo_url']="";
				$row['photo_url_info']=null;
				$row['photo_url_small']="";
				$row['photo_url_small_info']=null;
			}
			$row['photo_addtime']=date("Y-m-d",$row['photo_addtime']);
			$list_data[]=array_default_value($row,array('photo_url_info','photo_url_small_info'));
		}
	}
	
	
	$data['title']	="list_data";
	$data['data'] =$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


?>