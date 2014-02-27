<?php
$uid=$_G['gp_uid'];
if($uid)
{
	$user_info=DB::fetch_first("select realname from pre_common_member_profile where uid='".$uid."' ");
}

$field_set_list=array();


$field_set_list[0]['name']="zimeiti_apply_realname";
$field_set_list[0]['name_cn']="姓名";
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


$field_set_list[1]['name']="zimeiti_apply_role";
$field_set_list[1]['name_cn']="身份";
$field_set_list[1]['type']="select";
$field_set_list[1]['type_more'][]=array('key_value'=>'zishenmeitiren','key_name'=>'资深媒体人');
$field_set_list[1]['type_more'][]=array('key_value'=>'zhiyejingliren','key_name'=>'职业经理人');
$field_set_list[1]['type_more'][]=array('key_value'=>'zishenjiaolian','key_name'=>'资深教练');
$field_set_list[1]['type_more'][]=array('key_value'=>'PGAjiaolian','key_name'=>'PGA教练');
$field_set_list[1]['type_more'][]=array('key_value'=>'shichangzongjian','key_name'=>'市场总监');
$field_set_list[1]['type_more'][]=array('key_value'=>'qiuhuijingli','key_name'=>'球会经理');
$field_set_list[1]['type_more'][]=array('key_value'=>'qiuhuizongjingli','key_name'=>'球会总经理');
$field_set_list[1]['type_more'][]=array('key_value'=>'qiujuzhuanjia','key_name'=>'球具专家');
$field_set_list[1]['type_more'][]=array('key_value'=>'qiuchangshejishi','key_name'=>'球场设计师');
$field_set_list[1]['type_more'][]=array('key_value'=>'saishijingli','key_name'=>'赛事经理');
$field_set_list[1]['type_more'][]=array('key_value'=>'saishizongjian','key_name'=>'赛事总监');
$field_set_list[1]['type_more'][]=array('key_value'=>'CGAcaipan','key_name'=>'CGA裁判');
$field_set_list[1]['type_more'][]=array('key_value'=>'guojicaipan','key_name'=>'国际裁判');
$field_set_list[1]['type_more'][]=array('key_value'=>'pinglunyuan','key_name'=>'评论员');
$field_set_list[1]['type_more'][]=array('key_value'=>'zhiyeqiuyuan','key_name'=>'职业球员');
$field_set_list[1]['type_more'][]=array('key_value'=>'yeyuqiuyuan','key_name'=>'业余球员');
$field_set_list[1]['type_more'][]=array('key_value'=>'qingshaonianqiuyuan','key_name'=>'青少年球员');
$field_set_list[1]['type_more'][]=array('key_value'=>'qita','key_name'=>'其他');
$field_set_list[1]['max_size']="50";
$field_set_list[1]['value']="B";


$field_set_list[2]['name']="mobile";
$field_set_list[2]['name_cn']="手机号";
$field_set_list[2]['type']="input";
$field_set_list[2]['type_more']=null;
$field_set_list[2]['max_size']="50";
$field_set_list[2]['value']="";


$field_set_list[3]['name']="zimeiti_apply_card";
$field_set_list[3]['name_cn']="身份证号";
$field_set_list[3]['type']="input";
$field_set_list[3]['type_more']=null;
$field_set_list[3]['max_size']="50";
$field_set_list[3]['value']="12345689";


$field_set_list[4]['name']="zimeiti_apply_mobile";
$field_set_list[4]['name_cn']="推荐人手机号";
$field_set_list[4]['type']="input";
$field_set_list[4]['type_more']=null;
$field_set_list[4]['max_size']="50";
$field_set_list[4]['value']="18710066977";


$field_set_list[5]['name']="zimeiti_apply_intro";
$field_set_list[5]['name_cn']="个人介绍";
$field_set_list[5]['type']="textarea";
$field_set_list[5]['type_more']=null;
$field_set_list[5]['max_size']="300";
$field_set_list[5]['value']="这个人很懒，什么也没留下。。";


$field_set_list[6]['name']="zimeiti_apply_sex";
$field_set_list[6]['name_cn']="性别";
$field_set_list[6]['type']="radio";
$field_set_list[6]['type_more'][]=array('key_value'=>'M','key_name'=>'男');
$field_set_list[6]['type_more'][]=array('key_value'=>'N','key_name'=>'女');
$field_set_list[6]['max_size']="50";
$field_set_list[6]['value']="N";


$field_set_list[7]['name']="zimeiti_apply_fenzhan";
$field_set_list[7]['name_cn']="分站";
$field_set_list[7]['type']="checkbox";
$field_set_list[7]['type_more'][]=array('key_value'=>'1','key_name'=>'天津站');
$field_set_list[7]['type_more'][]=array('key_value'=>'2','key_name'=>'北京站');
$field_set_list[7]['type_more'][]=array('key_value'=>'3','key_name'=>'上海站');
$field_set_list[7]['type_more'][]=array('key_value'=>'4','key_name'=>'广州站');
$field_set_list[7]['max_size']="50";
$field_set_list[7]['value']="2,3";


return $field_set_list;