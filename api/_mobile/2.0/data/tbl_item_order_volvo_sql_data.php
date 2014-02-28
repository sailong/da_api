<?php
$sql=" insert into tbl_item_order_volvo (`order_id`,`qiancheng`,`family_name`,`name`,`year`,`month`,`day`,`phone`,`email`,`province`,`city`,`address`,`postcode`,`addtime`,`user_device`,`field_name`) values ('{value_order_id}','{value_qiancheng}','{value_family_name}','{value_name}','{value_year}','{value_month}','{value_day}','{value_phone}','{value_email}','{value_province}','{value_city}','{value_address}','{value_postcode}','{value_addtime}','{value_user_device}','{value_field_name}') ";
return $sql;

?>