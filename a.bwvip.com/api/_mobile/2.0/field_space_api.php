<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
if($ac=="field_space"){
	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	$data = array();
	if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
	{
		$data['dz_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8";
		$data['mlh_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='ios' and field_uid=1186 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/shang-hai-mei-lan-hu-gao-er/id661625407?ls=1&mt=8";
		$data['ns_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='ios' and field_uid=1160 order by app_version_addtime desc limit 1 ");//"http://www.baidu.com";
		
	}
	else if(strpos($userAgent,"Android"))
	{
		$data['dz_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
		$data['mlh_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='android' and field_uid=1186 order by app_version_addtime desc limit 1 ");
		$data['ns_down_url'] = DB::result_first("select * from tbl_app_version where app_version_type='android' and field_uid=1160 order by app_version_addtime desc limit 1 ");
	}
	
	api_json_result(1,0,'',$data);
}


?>