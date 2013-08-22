<?php
/*
*
* system_api.php
* by zhanglong 2013-05-21
* field app 系统相关
*
*/

if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}

$ac=$_G['gp_ac'];
$type_more = array(
	'qiutong'=>'qiutong',
	'qiuchang'=>'field_golf',
	'jiudian'=>'hotel_intro,hotel_room,hotel_canyin,hotel_meet,hotel_yule,hotel_spa',
	'bieshu'=>'',
	'canting'=>'',
	'qiudaotu'=>'qiudaotu'
	);

//系统更新
if($ac=="category_list")
{
	$field_uid = $_G['gp_field_uid'];
	if(empty($field_uid))
	{
		api_json_result(1,1,"缺少参数field_uid",null);
	}

	$list=DB::query("select category_id,category_name,field_uid,category_type,category_sort,category_addtime from tbl_category where 1 and field_uid='".$field_uid."' order by category_addtime desc");
	
	while($row = DB::fetch($list) )
	{	
		$row['category_type_more'] = $type_more[$row['category_type']];
		$list_data[$row['category_type']][]=array_default_value($row);
	}
	$return_arr = array();
	foreach($list_data as $val){
		$return_arr[] = $val;
	}
	unset($list_data);
	$data['title']		=   "data";
	$data['data']		=	$return_arr;
	api_json_result(1,0,"返回成功",$data);
	
}

if($ac=="field_about_list")
{
	$field_uid = $_G['gp_field_uid'];
	$category_id = $_G['gp_category_id'];
	if(empty($category_id) || empty($field_uid))
	{
		api_json_result(1,1,"缺少参数category_id或category_type",null);
	}

	$list=DB::query("select about_id,about_name,field_uid,about_type,about_content,about_tel,about_tel2,about_pic,language,about_addtime from tbl_field_about where 1=1 and field_uid='".$field_uid."' and category_id='".$category_id."' order by about_addtime desc");
	
	while($row = DB::fetch($list))
	{
		$row['about_pic'] = $site_url.'/'.$row['about_pic'];
		$row['about_addtime'] = date('Y-m-d', $row['about_addtime']);
		$list_data[]=array_default_value($row);
	}
	
	$data['title']		= "data";
	$data['data']		= $list_data;
	api_json_result(1,0,"返回成功",$data);
	
}

if($ac=="qt_category")
{
	$field_uid = $_G['gp_field_uid'];
	$category_id = $_G['gp_category_id'];
	if(empty($category_id) || empty($field_uid))
	{
		api_json_result(1,1,"缺少参数category_id或category_type",null);
	}

	$list=DB::query("select qiutong_id,uid,qiutong_number,qiutong_name,qiutong_name_en,qiutong_photo,qiutong_content,qiutong_addtime field_uid from tbl_qiutong where 1 and field_uid='".$field_uid."' order by qiutong_addtime desc");
	
	while($row = DB::fetch($list) )
	{
		$row['qiutong_photo'] = $site_url.'/'.$row['qiutong_photo'];
		$row['qiutong_addtime'] = date('Y-m-d', $row['qiutong_addtime']);
		$list_data[]=array_default_value($row);
	}

	$data['title']		= "data";
	$data['data']		=	array_default_value($list_data);
	api_json_result(1,0,"返回成功",$data);
	
}
if($ac=="ct_category")
{
	$field_uid = $_G['gp_field_uid'];
	$category_id = $_G['gp_category_id'];
	if(empty($category_id) || empty($field_uid))
	{
		api_json_result(1,1,"缺少参数category_id或category_type",null);
	}

	$list=DB::query("select field_1stmenu_id,field_uid,field_1stmenu_type,field_1stmenu_name,field_1stmenu_name_en,field_1stmenu_addtime from tbl_field_1stmenu where 1 and field_uid='".$field_uid."' order by field_1stmenu_addtime desc");
	
	while($row = DB::fetch($list) )
	{
		$row['field_1stmenu_addtime'] = date('Y-m-d', $row['field_1stmenu_addtime']);
		$list_data[]=array_default_value($row);
	}

	$data['title']		= "data";
	$data['data']		=	array_default_value($list_data);
	api_json_result(1,0,"返回成功",$data);
	
}

?>