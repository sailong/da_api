<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
//修改密码
if($ac=="bwm_reg")
{
	$qiancheng = urldecode($_G['gp_qiancheng']);
	$family_name = urldecode($_G['gp_family_name']);
	$name = urldecode($_G['gp_name']);
	$year = urldecode($_G['gp_year']);
	$month = urldecode($_G['gp_month']);
	$day = urldecode($_G['gp_day']);
	$phone = urldecode($_G['gp_phone']);
	if(!preg_match_type($phone,'phone')){
		api_json_result(1,1,"手机号格式不正确",$data);
	}
	$email = urldecode($_G['gp_email']);
	if(!preg_match_type($email,'email')){
		api_json_result(1,1,"电子邮件格式不正确",$data);
	}
	$province = urldecode($_G['gp_province']);
	$city = urldecode($_G['gp_city']);
	$address = urldecode($_G['gp_address']);
	$postcode = urldecode($_G['gp_postcode']);
	if(!preg_match_type($postcode,'postcode')){
		api_json_result(1,1,"邮政编码不正确",$data);
	}
	$watch_date = urldecode($_G['gp_watch_date']);
	$is_owners = $_G['gp_is_owners'];
	$bwm_cars = urldecode($_G['gp_bwm_cars']);
	$buy_car_date = urldecode($_G['gp_buy_car_date']);
	$learn_channels = urldecode($_G['gp_learn_channels']);
	$is_contact = $_G['gp_is_contact'];
	$is_readed = $_G['gp_is_readed'];
	$bwm_addtime = time();
	$bwm_adddate = date('Y年m月d日',$bwm_addtime);
	if(empty($is_readed)){
		$is_readed = 1;
	}
	$sql = "insert into tbl_bwm_game(qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime,bwm_adddate)";
	$sql .= " values('{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}','{$bwm_adddate}')";
	
    $res = DB::query($sql);
	if($res == false){
		api_json_result(1,1,"提交失败",null);
	}

    api_json_result(1,0,"国内注册用户，我们将寄送门票到您注册邮寄地址，海外注册用户烦请您在比赛当日至BMW大师赛现场领取门票，每日限领2张。",null);
}



if($ac == 'preg'){

	$str = $_G['gp_str'];
	$type = $_G['gp_type'];
	echo $str;
	echo '<br>';
	echo $type;
	echo '<br>';
	if(!preg_match_type($str,$type)){
		echo '正确';
	}
	else
	{
		echo '错误';
	}
}
function preg_match_type($str,$type="int")
{
	switch ($type)
	{
	case 'int':
	  $pattern = "/^[0-9]*$/";
	  break;  
	case 'phone':
	  $pattern = "/13[0-9]{9}|15[0-9]\d{8}|18[0-9]\d{8}/";
	  break;
    case 'email':
	  $pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
	  break;
	case 'postcode':
	  $pattern = "/^[0-9]\d{5}$/";
	  break;
	}
	
	return preg_match($pattern,$str);
}

?>
