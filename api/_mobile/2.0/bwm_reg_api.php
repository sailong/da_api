<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
//修改密码
if($ac=="bwm_reg")
{
	$qiancheng = $_G['qiancheng'];
	$family_name = $_G['family_name'];
	$name = $_G['name'];
	$year = $_G['year'];
	$month = $_G['month'];
	$day = $_G['day'];
	$phone = $_G['phone'];
	$email = $_G['email'];
	$province = $_G['province'];
	$city = $_G['city'];
	$address = $_G['address'];
	$postcode = $_G['postcode'];
	$watch_date = $_G['watch_date'];
	$is_owners = $_G['is_owners'];
	$bwm_cars = $_G['bwm_cars'];
	$buy_car_date = $_G['buy_car_date'];
	$learn_channels = $_G['learn_channels'];
	$is_contact = $_G['is_contact'];
	$is_readed = $_G['is_readed'];
	$bwm_addtime = time();
	if(empty($is_readed)){
		$is_readed = 1;
	}
	$sql = "insert into tbl_bwm_game(qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime)";
	$sql .= " values('{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}')";
	
    $res = DB::query($sql);
	if($res == false){
		api_json_result(1,1,"提交失败",null);
	}

    api_json_result(1,0,"提交成功",null);
}

?>
