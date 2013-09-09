<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}
$userAgent = $_SERVER['HTTP_USER_AGENT'];

if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$user_device = 'IOS';
}else if(strpos($userAgent,"Android") || $_G['gp_from'] == 'android')
{
	$user_device = 'Android';
}else{
	$user_device = '';
}

$ac=$_G['gp_ac'];
//修改密码
if($ac=="bwm_reg")
{	
	$field_uid = $_G['gp_field_uid'];
	if($field_uid == 0)
	{
		$field_name = '大正客户端';
	}else{
		$field_info = DB::fetch_first("select * from pre_common_field where uid='{$field_uid}' limit 1");
		$field_name = $field_info['fieldname'];
	}
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
	$is_readed = '是';//$_G['gp_is_readed'];
	/* if(!empty($is_readed))
	{
		$is_readed = '是';
	}else{
		$is_readed = '否';
	} */
	if(!empty($is_contact))
	{
		$is_contact = '是';
	}else{
		$is_contact = '否';
	}
	if(!empty($is_owners))
	{
		$is_owners = '是';
	}else{
		$is_owners = '否';
	}
	
	$bwm_addtime = time();
	$bwm_adddate = date('Y年m月d日 H:i:s',$bwm_addtime);
	
	$sql = "insert into tbl_user_ticket_bmw(qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime,bwm_adddate,user_device,field_name)";
	$sql .= " values('{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}','{$bwm_adddate}','{$user_device}','{$field_name}')";
	
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
