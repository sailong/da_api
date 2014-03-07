<?php
//亚运会世锦赛选拔赛    66

$uid=$_G['gp_uid'];
if($uid)
{
	$user_info=DB::fetch_first("select realname from pre_common_member_profile where uid='".$uid."' ");
}
$list=DB::query("select fenzhan_id,fenzhan_name from tbl_fenzhan where event_id='66'");
while($row=DB::fetch($list))
{
	$fenzhan_arr[] = $row['fenzhan_name'];
}

$field_set_list=array();
	
$field_set_list[0]['name']="baoming_realname";
$field_set_list[0]['name_cn']="姓　　名";
$field_set_list[0]['type']="input";
$field_set_list[0]['type_more']=null;
$field_set_list[0]['max_size']="50";
if($user_info['realname'])
{
	$field_set_list[0]['value']=$user_info['realname'];
}
else
{
	$field_set_list[0]['value']="";
}


$field_set_list[1]['name']="baoming_sex";
$field_set_list[1]['name_cn']="姓　　别";
$field_set_list[1]['type']="radio";
$field_set_list[1]['type_more']=array('男','女');
/* $field_set_list[1]['type_more'][]=array('key_value'=>'男','key_name'=>'男');
$field_set_list[1]['type_more'][]=array('key_value'=>'女','key_name'=>'女'); */
$field_set_list[1]['max_size']="50";

$field_set_list[2]['name']="baoming_mobile";
$field_set_list[2]['name_cn']="手  机  号";
$field_set_list[2]['type']="input";
$field_set_list[2]['max_size']="50";

$field_set_list[3]['name']="baoming_email";
$field_set_list[3]['name_cn']="邮　　箱";
$field_set_list[3]['type']="input";
$field_set_list[3]['max_size']="50";

$field_set_list[4]['name']="baoming_card";
$field_set_list[4]['name_cn']="证  件  号";
$field_set_list[4]['type']="input";
$field_set_list[4]['max_size']="50";

$field_set_list[5]['name']="baoming_chadian";
$field_set_list[5]['name_cn']="差　　点";
$field_set_list[5]['type']="input";
$field_set_list[5]['max_size']="50";

$field_set_list[6]['name']="baoming_zige";
$field_set_list[6]['name_cn']="报名资格";
$field_set_list[6]['type']="radio";
$field_set_list[6]['type_more']=array('国家队现役运动员','业余比赛前三名运动员','海外运动员','中高协推荐运动员');
/* $field_set_list[6]['type_more'][]=array('key_value'=>'国家队现役运动员','key_name'=>'国家队现役运动员');
$field_set_list[6]['type_more'][]=array('key_value'=>'业余比赛前三名运动员','key_name'=>'业余比赛前三名运动员');
$field_set_list[6]['type_more'][]=array('key_value'=>'海外运动员','key_name'=>'海外运动员');
$field_set_list[6]['type_more'][]=array('key_value'=>'中高协推荐运动员','key_name'=>'中高协推荐运动员'); */
$field_set_list[6]['max_size']="50";
/* if(!empty($fenzhan_arr))
{
	$field_set_list[7]['name']="fenzhan_names";
	$field_set_list[7]['name_cn']="选择比赛";
	$field_set_list[7]['type']="radio";
	$field_set_list[7]['type_more']=$fenzhan_arr;
} */

//array('珠海金湾站','天津滨海湖站');
/* $field_set_list[6]['type_more'][]=array('key_value'=>'142','key_name'=>'珠海金湾站');
$field_set_list[6]['type_more'][]=array('key_value'=>'143','key_name'=>'天津滨海湖站'); */
$field_set_list[7]['name']="fenzhan_names";
$field_set_list[7]['name_cn']="选择比赛";
$field_set_list[7]['type']="radio";
$field_set_list[7]['type_more']=array('珠海金湾站','天津滨海湖站');
$field_set_list[7]['max_size']="50";

$field_set_list[8]['name']="baoming_is_zidai_qiutong";
$field_set_list[8]['name_cn']="自带球童";
$field_set_list[8]['type']="radio";
$field_set_list[8]['type_more']=array('是','否');
/* $field_set_list[8]['type_more'][]=array('key_value'=>'Y','key_name'=>'是');
$field_set_list[8]['type_more'][]=array('key_value'=>'N','key_name'=>'否'); */
$field_set_list[8]['max_size']="50";

	
return $field_set_list;