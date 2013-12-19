<?php
$field_set_list=array();

//is_owners
$field_set_list[9]['name']="is_owners";
$field_set_list[9]['name_cn']="是否是车主";
$field_set_list[9]['type']="select";
$field_set_list[9]['type_more']=array('是','否');
$field_set_list[9]['max_size']="50";
$field_set_list[9]['value']="";
//is_contact
$field_set_list[10]['name']="is_contact";
$field_set_list[10]['name_cn']="是否与当地经营商取得联系";
$field_set_list[10]['type']="select";
$field_set_list[10]['type_more']=array('是','否');
$field_set_list[10]['max_size']="50";
$field_set_list[10]['value']="";

return $field_set_list;