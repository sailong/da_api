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

//系统更新
if($ac=="update")
{
	$app_version_type=$_G['gp_type'];
	$field_uid=$_G['gp_client_id'];
	if($field_uid)
	{
		$sql=" and field_uid='".$field_uid."' ";
	}
	else
	{
		$sql=" and field_uid='0' ";
	}
	$version =DB::fetch_first( "select app_version_type,app_version_number,app_version_name,app_version_content,app_version_file,app_version_is_important,app_version_addtime from tbl_app_version where app_version_type ='".$app_version_type."' ".$sql." order by app_version_addtime desc limit 1  ");
	$version['app_version_addtime']=date("Y-m-d G:i:s",$version['app_version_addtime']);


	$data['title']		= "data";
	$data['data']		=	array_default_value($version);
	api_json_result(1,0,"返回成功",$data);
	
}

?>