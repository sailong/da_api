<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];

if($ac=="field_space"){
	
	
	$top_pic="http://www.bwvip.com/images/wap/field_space/top.jpg";
	$mlh_pic="http://www.bwvip.com/images/wap/field_space/meilanhu.jpg";
	$ns_pic="http://www.bwvip.com/images/wap/field_space/nanshan.jpg";
	
	
	$mlh_list = array(
		'name' => '美兰湖高尔夫俱乐部',
		'pic' => $mlh_pic,
		'jingdu' => '121.375781',
		'weidu'  => '31.411205'
	);
	$ns_list = array(
		'name' => '南山庄园高尔夫俱乐部',
		'pic' => $ns_pic,
		'jingdu' => '120.48',
		'weidu'  => '37.591306'
	);

	//$list['dz_down_url_ios'] = DB::fetch_first("select * from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$ios_mlh = DB::fetch_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$ios_ns = DB::fetch_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1160 order by app_version_addtime desc limit 1 ");

	//$list['dz_down_url_android'] = DB::fetch_first("select * from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$android_mlh = DB::fetch_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$android_ns = DB::fetch_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1160 order by app_version_addtime desc limit 1 ");
	
	if(!empty($ios_mlh)){
		$mlh_list['down_url'] = $ios_mlh['app_version_file'];
		$ios_list[] = $mlh_list;
	}
	
	if(!empty($ios_ns)){
		$ns_list['down_url'] = $ios_ns['app_version_file'];
		$ios_list[] = $ns_list;
	}
	
	if(!empty($android_mlh)){
		$mlh_list['down_url'] = $android_mlh['app_version_file'];
		$android_list[] = $mlh_list;
	}
	
	if(!empty($android_ns)){
		$ns_list['down_url'] = $android_ns['app_version_file'];
		$android_list[] = $ns_list;
	}
	
	$data['title'] = 'data';
	$data['data'] = array('top_pic'=>$top_pic,'ios'=>$ios_list,'android'=>$android_list);
	api_json_result(1,0,"成功",$data);
}


?>