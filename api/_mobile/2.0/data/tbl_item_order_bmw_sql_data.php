<?php
$sql=" insert into tbl_item_order_bmw(`order_id`,`qiancheng`,`family_name`,`name`,`year`,`month`,`day`,`phone`,`email`,`province`,`city`,`address`,`postcode`,`watch_date`,`is_owners`,`bwm_cars`,`buy_car_date`,`learn_channels`,`is_contact`,`bwm_adddate`,`addtime`,`user_device`,`field_name`) values ('{value_order_id}','{value_qiancheng}','{value_family_name}','{value_name}','{value_year}','{value_month}','{value_day}','{value_phone}','{value_email}','{value_province}','{value_city}','{value_address}','{value_postcode}','{value_watch_date}','{value_is_owners}','{value_bwm_cars}','{value_buy_car_date}','{value_learn_channels}','{value_is_contact}','{value_bwm_adddate}','{value_addtime}','{value_user_device}','{value_field_name}') ";
return $sql;

?>