<?php
$uid=$_G['gp_uid'];
if($uid)
{
	$user_info=DB::fetch_first("select realname from pre_common_member_profile where uid='".$uid."' ");
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
	$field_set_list[1]['name_cn']="性　　别";
	$field_set_list[1]['type']="radio";
	$field_set_list[1]['type_more'][]=array('key_value'=>'男','key_name'=>'男');
	$field_set_list[1]['type_more'][]=array('key_value'=>'女','key_name'=>'女');
	$field_set_list[1]['max_size']="50";
	
	/* $field_set_list[6]['name']="zimeiti_apply_sex";
	$field_set_list[6]['name_cn']="性别";
	$field_set_list[6]['type']="radio";
	$field_set_list[6]['type_more'][]=array('key_value'=>'M','key_name'=>'男');
	$field_set_list[6]['type_more'][]=array('key_value'=>'N','key_name'=>'女');
	$field_set_list[6]['max_size']="50";
	$field_set_list[6]['value']="N"; */


	/*
	$field_set_list[2]['name']="baoming_card";
	$field_set_list[2]['name_cn']="身份证号";
	$field_set_list[2]['type']="input";
	$field_set_list[2]['max_size']="50";
	*/
	
	$field_set_list[2]['name']="baoming_chadian";
	$field_set_list[2]['name_cn']="差　　点";
	$field_set_list[2]['type']="input";
	$field_set_list[0]['type_more']=null;
	$field_set_list[2]['max_size']="50";
	
	$field_set_list[3]['name']="baoming_is_huang";
	$field_set_list[3]['name_cn']="是否车主";
	$field_set_list[3]['type']="radio";
	//$field_set_list[3]['type_more']=array('是','否');
	$field_set_list[3]['type_more'][]=array('key_value'=>'1','key_name'=>'是');
	$field_set_list[3]['type_more'][]=array('key_value'=>'0','key_name'=>'否');
	$field_set_list[3]['max_size']="50";

	$field_set_list[4]['name']="fenzhan_ids";
	$field_set_list[4]['name_cn']="分　　站";
	/* $field_set_list[4]['type']="radio";
	$field_set_list[4]['type_more']=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州'); */
	$field_set_list[4]['type']="checkbox";
	$field_set_list[4]['type_more'][]=array('key_value'=>'1','key_name'=>'天津站');
	$field_set_list[4]['type_more'][]=array('key_value'=>'2','key_name'=>'北京站');
	$field_set_list[4]['type_more'][]=array('key_value'=>'3','key_name'=>'上海站');
	$field_set_list[4]['type_more'][]=array('key_value'=>'4','key_name'=>'广州站');
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