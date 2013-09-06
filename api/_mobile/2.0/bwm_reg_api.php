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
	$bwm_addtime = time();
	$bwm_adddate = date('Y年m月d日 H:i:s',$bwm_addtime);
	
	$ticket_info = get_ticket_info(26);
	$user_ticket_age = intval(date('Y',time()))-intval(($year?$year:date('Y',time())));
	
	$watch_date_arr = explode(',',$watch_date);
	
	$user_ticket_data = array(
		'ticket_id' => $ticket_info['ticket_id'],
		'event_id' => $ticket_info['event_id'],
		'ticket_type' => $ticket_info['ticket_type'],
		'ticket_starttime' =>$ticket_info['ticket_starttime'],
		'ticket_endtime' => $ticket_info['ticket_endtime'],
		'ticket_times' => $ticket_info['ticket_times'],
		
		'user_ticket_realname' => $family_name.$name,
		'user_ticket_sex' => $qiancheng == '先生' ? '男':'女',
		'user_ticket_age' => $user_ticket_age,
		'user_ticket_address' => $province.$city.$address,
		'user_ticket_mobile' => $phone
	);
	
	$nian = date('Y',time());
	$insert_ids = array();
	$watch_num = 0;
	foreach($watch_date_arr as $key=>$val){
		if(empty($val))
		{
			continue;
		}
		$watch_num++;
		$yue = $val[0].$val[1];
		$ri = $val[5].$val[6];
		$ticket_starttime = mktime(0,0,0,$yue,$ri,$nian);
		$ticket_enttime = $ticket_starttime+86400;
		$user_ticket_data['ticket_starttime']=$ticket_starttime;
		$user_ticket_data['ticket_endtime']=$ticket_enttime;
		//echo strtotime("{$nian}-{$yue}-{$ri}").'<br>';die;
		//echo date('Y-m-d',mktime(0,0,0,$yue,$ri,$nian)).'<br>';
		$insert_ids[] = $user_ticket_id = insert_into_user_ticket($user_ticket_data);
		
	}
	$user_ticket_ids = implode(",",$insert_ids);
	if(count($insert_ids) != $watch_num){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_ids})";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	
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
	
	/* $erweima_path = erweima();
	
	 	if(empty($erweima_path)) {
			api_json_result(1,1,"二维码生成失败",null);
		}
	*/	
	$sql = "insert into tbl_user_ticket_bmw(user_ticket_id,user_ticket_ids,qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime,bwm_adddate,user_device,field_name)";
	$sql .= " values('{$user_ticket_id}','{$user_ticket_ids}','{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}','{$bwm_adddate}','{$user_device}','{$field_name}')";
	
    $rs = DB::query($sql);
	if($rs == false){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_ids})";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	$insert_id = DB::insert_id();
	
	$sql = "update tbl_user_ticket set out_idtype='_bmw',out_id='{$insert_id}' where user_ticket_id in({$user_ticket_ids})";
	$rs = DB::query($sql);
	if(!$rs){
		$sql = "delete from tbl_user_ticket where user_ticket_id in({$user_ticket_ids})";
		DB::query($sql);
		$sql = "delete from tbl_user_ticket_bmw where id='{$insert_id}'";
		DB::query($sql);
		api_json_result(1,1,"提交失败",null);
	}
	/* $data['title']='erweima';
	$data['data']=$site_url.$erweima_path;*/
    api_json_result(1,0,"国内注册用户，我们将寄送门票到您注册邮寄地址，海外注册用户烦请您在比赛当日至BMW大师赛现场领取门票，每日限领2张。",$data);
}

function get_ticket_info($ticket_id)
{
	$sql = "select * from tbl_ticket where ticket_id='{$ticket_id}'";
	
    $ticket_info = DB::fetch_first($sql);
	
	return $ticket_info;
}

function insert_into_user_ticket($data_list)
{
	if(empty($data_list))
	{
		return false;
	}
	$data_list['user_ticket_addtime'] = time();
	$data_list['user_ticket_code'] = get_randmod_str();
	$fields = array();
	$values = array();
	foreach($data_list as $key=>$val){
		if(!empty($val)){
			$fields[$key] = $key;
			$values[$val] = $val;
		}
	}
	
	$sql = "insert into tbl_user_ticket(".implode(',',$fields).") values('".implode("','",$values)."')";
	$rs = DB::query($sql);
	if($rs){
		$last_id = DB::insert_id();
		return $last_id;
	}
	return $rs;
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


//生成二维码成功返回路径，失败返回 false
function erweima()
{
	$phone = mt_rand(1000000000,9999999999);
	//如果没有就生成二维码
	$path_erweima_core = dirname(dirname(dirname(dirname(__FILE__))));
	
	include $path_erweima_core."/tool/phpqrcode/qrlib.php";
	$prefix = $path_erweima_core;
	$save_path="/upload/erweima/";
	$now_date = date("Ymd",time());
	$full_save_path=$path_erweima_core.$save_path.$now_date."/";

	if(!file_exists($prefix.$save_path))
	{
		mkdir($prefix.$save_path);
	}
	if(!file_exists($full_save_path))
	{
		$a = mkdir($full_save_path);
	}
	
	$pic_filename=$full_save_path.$phone.".png";
	$sql_save_path = $save_path.$now_date.'/'.$phone.".png";
	$errorCorrectionLevel = "L";
	$matrixPointSize=9;
	$margin=1;
	
	QRcode::png($phone, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
	
	if(file_exists($pic_filename))
	{
		return $sql_save_path;
	}
	else
	{
		return false;
	}
}

//获取随机字符串
function get_randmod_str(){
	$str = 'abcdABCefgD69EFhigkGHI7nm8JKpqMNrs3PQRtuS5vw4TxyU1VWzXYZ20';
	$len = strlen($str); //得到字串的长度;

	//获得随即生成的积分卡号
	$s = rand(0, 1);
	$serial = '';

	for($s=1;$s<=10;$s++)
	{
	   $key     = rand(0, $len-1);//获取随机数
	   $serial .= $str[$key];
	}

   //strtoupper是把字符串全部变为大写
   $serial = strtoupper(substr(md5($serial.time()),10,10));
   if($s)
   {
	  $serial = strtoupper(substr(md5($serial),mt_rand(0,22),10));
   }
   
   return $serial;
}

?>
