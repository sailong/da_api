<?php
$field_set_list=array();
	
	$field_set_list[0]['name']="event_apply_realname";
	$field_set_list[0]['name_cn']="姓　　名";
	$field_set_list[0]['type']="input";
	$field_set_list[0]['max_size']="50";

	$field_set_list[1]['name']="event_apply_sex";
	$field_set_list[1]['name_cn']="姓　　别";
	$field_set_list[1]['type']="radio";
	$field_set_list[1]['type_more']=array('男','女');
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
	$field_set_list[3]['type_more']=array('是','否');
	$field_set_list[3]['max_size']="50";

	$field_set_list[4]['name']="event_apply_fenzhan";
	$field_set_list[4]['name_cn']="分　　站";
	$field_set_list[4]['type']="radio";
	$field_set_list[4]['type_more']=array('5/11天津','5/24广州','5/31深圳','6/15杭州','6/21上海','6/29长沙','7/19北京','7/26大连','8/9郑州','8/24成都','8/30苏州','9/7福州');
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