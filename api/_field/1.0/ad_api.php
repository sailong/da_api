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
	
	
	
	
	$list=DB::query("select category_id,category_name,field_uid,category_type,category_sort,category_addtime from tbl_category where 1 and field_uid='".$field_uid."' order by category_addtime desc");
	
	while($row = DB::fetch($list))
	{	
		$row['category_type_more'] = $type_more[$row['category_type']];
		$category_data[$row['category_type']][]=array_default_value($row);
	}
	
	
	
	
	
	
	
	
	
		$data['title']		= "data";
		$data['data']     =  array('ad_list'=>$list_data,'category_list'=>$category_data);
	if(!empty($list_data))
	{
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}

function category_father($key_val)
{
	$list = array(
		array(
			'id'   => 'julebu',
			'name' => '俱乐部介绍',//field_golf,field_hotel,field_huisuo,field_meet
			'type_more'=> 'field_golf,field_hotel,field_huisuo,field_meet'
		),
		array(
			'id'   => 'qiudaotu',
			'name' => '球道图',//qiudaotu
			'type_more'=> 'qiudaotu'
		),
		array(
			'id'   => 'qiutong',
			'name' => '球童介绍',//qiutong
			'type_more'=> 'qiutong'
		),
		array(
			'id'   => 'canyin',
			'name' => '餐饮介绍',//canyin
			'type_more'=> 'canyin'
		),
		array(
			'id'   => 'bieshu',
			'name' => '别墅项目',//mingren_photo,mingren_intro,mingren_room,mingren_yuyue
			'type_more'=> 'mingren_photo,mingren_intro,mingren_room,mingren_yuyue'
		),
		array(
			'id'   => 'jiudian',
			'name' => '酒店项目',//hotel_intro,hotel_room,hotel_canyin,hotel_meet,hotel_yule,hotel_spa
			'type_more'=> 'hotel_intro,hotel_room,hotel_canyin,hotel_meet,hotel_yule,hotel_spa'
		)
	);
	if($key_val == 'key_val')
	{
		foreach($list as $key=>$val)
		{
			unset($list[$key]);
			$list[$val['id']]=$val['name'];
		}
	}
	
	if($key_val == 'type_more')
	{
		foreach($list as $key=>$val)
		{
			unset($list[$key]);
			$list[$val['id']]=$val['type_more'];
		}
	}
	return $list;
}






?>