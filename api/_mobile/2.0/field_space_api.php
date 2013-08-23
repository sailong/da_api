<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];

if($ac=="field_space"){
	

	//$list['dz_down_url_ios'] = DB::fetch_first("select * from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$ios_down_mlh = DB::fetch_first("select * from tbl_app_version where app_version_type='ios' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$ios_down_ns = DB::fetch_first("select * from tbl_app_version where app_version_type='ios' and field_uid=1160 order by app_version_addtime desc limit 1 ");
	

	//$list['dz_down_url_android'] = DB::fetch_first("select * from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$android_down_mlh = DB::fetch_first("select * from tbl_app_version where app_version_type='android' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$android_down_ns = DB::fetch_first("select * from tbl_app_version where app_version_type='android' and field_uid=1160 order by app_version_addtime desc limit 1 ");
	if($android_down_mlh){
		$android_down_mlh['top_img']="http://www.bwvip.com/images/wap/clubspace/top1.jpg";
		$android_down_mlh['down_img']="http://www.bwvip.com/images/wap/clubspace/but1.jpg";
		$android_list[] = $android_down_mlh;
	}else{
		unset($android_down_mlh);
	}
	if($android_down_ns){
		$android_down_ns['top_img']="http://www.bwvip.com/images/wap/clubspace/top2.jpg";
		$android_down_ns['down_img']="http://www.bwvip.com/images/wap/clubspace/but2.jpg";
		$android_list[] = $android_down_ns;
	}else{
		unset($android_down_ns);
	}
	
	
	
	
	if(ios_down_mlh){
		$ios_down_mlh['top_img']="http://www.bwvip.com/images/wap/clubspace/top1.jpg";
		$ios_down_mlh['down_img']="http://www.bwvip.com/images/wap/clubspace/but1.jpg";
		$ios_list[] = $ios_down_mlh;
	}else{
		unset($ios_down_mlh);
	}
	if($ios_down_ns){
		$ios_down_ns['top_img']="http://www.bwvip.com/images/wap/clubspace/top2.jpg";
		$ios_down_ns['down_img']="http://www.bwvip.com/images/wap/clubspace/but2.jpg";
		$ios_list[] = $ios_down_ns;
	}else{
		unset($ios_down_ns);
	}
	
	
	
	
	
	
	
	
	$data['title'] = 'data';
	$data['data'] = array('ios'=>$ios_list,'android'=>$android_list);
	api_json_result(1,0,"成功",$data);
}


?>