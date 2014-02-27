<?php
$uid=$_G['gp_uid'];
if($uid)
{
	$user_info=DB::fetch_first("select realname from pre_common_member_profile where uid='".$uid."' ");
}

$field_set_list=array();
	
	$field_set_list[0]['name']="event_apply_realname";
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

	$field_set_list[1]['name']="event_apply_sex";
	$field_set_list[1]['name_cn']="姓　　别";
	$field_set_list[1]['type']="radio";
	$field_set_list[1]['type_more'][]=array('key_value'=>'1','key_name'=>'男');
	$field_set_list[1]['type_more'][]=array('key_value'=>'2','key_name'=>'女');
	$field_set_list[1]['max_size']="50";

	/*
	$field_set_list[2]['name']="event_apply_card";
	$field_set_list[2]['name_cn']="身份证号";
	$field_set_list[2]['type']="input";
	$field_set_list[2]['max_size']="50";
	*/
	
	$field_set_list[2]['name']="event_apply_chadian";
	$field_set_list[2]['name_cn']="差　　点";
	$field_set_list[2]['type']="input";
	$field_set_list[2]['max_size']="50";
	
	$field_set_list[3]['name']="event_apply_is_huang";
	$field_set_list[3]['name_cn']="是否车主";
	$field_set_list[3]['type']="radio";
	//$field_set_list[3]['type_more']=array('是','否');
	$field_set_list[3]['type_more'][]=array('key_value'=>'1','key_name'=>'是');
	$field_set_list[3]['type_more'][]=array('key_value'=>'0','key_name'=>'否');
	$field_set_list[3]['max_size']="50";

	$field_set_list[4]['name']="event_apply_fenzhan";
	$field_set_list[4]['name_cn']="分　　站";
	$field_set_list[4]['type']="radio";
	//$field_set_list[4]['type_more']=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州');
	$field_set_list[4]['type_more'][]=array('key_value'=>'5/11天津','key_name'=>'5/11天津');
	$field_set_list[4]['type_more'][]=array('key_value'=>'5/24广州','key_name'=>'5/24广州');
	$field_set_list[4]['type_more'][]=array('key_value'=>'5/31深圳','key_name'=>'5/31深圳');
	$field_set_list[4]['type_more'][]=array('key_value'=>'6/15杭州','key_name'=>'6/15杭州');
	$field_set_list[4]['type_more'][]=array('key_value'=>'6/21上海','key_name'=>'6/21上海');
	$field_set_list[4]['type_more'][]=array('key_value'=>'6/29长沙','key_name'=>'6/29长沙');
	$field_set_list[4]['type_more'][]=array('key_value'=>'7/19北京','key_name'=>'7/19北京');
	$field_set_list[4]['type_more'][]=array('key_value'=>'7/26大连','key_name'=>'7/26大连');
	$field_set_list[4]['type_more'][]=array('key_value'=>'8/9郑州','key_name'=>'8/9郑州');
	$field_set_list[4]['type_more'][]=array('key_value'=>'8/24成都','key_name'=>'8/24成都');
	$field_set_list[4]['type_more'][]=array('key_value'=>'8/24成都','key_name'=>'8/24成都');
	$field_set_list[4]['type_more'][]=array('key_value'=>'8/30苏州','key_name'=>'8/30苏州');
	$field_set_list[4]['type_more'][]=array('key_value'=>'9/7福州','key_name'=>'9/7福州');
	$field_set_list[4]['max_size']="50";

/*
	$field_set_list[4]['name']="event_id";
	$field_set_list[4]['type']="hidden";
	$field_set_list[4]['max_size']="11";

	$field_set_list[5]['name']="uid";
	$field_set_list[5]['type']="hidden";
	$field_set_list[5]['max_size']="11";
*/

return $field_set_list;