<?php

$field_set_list=array();

$field_set_list[0]['name']="mobile";
$field_set_list[0]['name_cn']="被邀请人的手机号";
$field_set_list[0]['type']="input";
$field_set_list[0]['type_more']=null;
$field_set_list[0]['max_size']="50";
$field_set_list[0]['value']="";

$field_set_list[1]['name']="guanxi";
$field_set_list[1]['name_cn']="与您的关系";
$field_set_list[1]['type']="select";
$field_set_list[1]['type_more'][]=array('key_value'=>'球友','key_name'=>'球友');
$field_set_list[1]['type_more'][]=array('key_value'=>'朋友','key_name'=>'朋友');
$field_set_list[1]['max_size']="50";
$field_set_list[1]['value']="";


return $field_set_list;