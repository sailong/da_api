<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
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
	$sql_save_path = $save_path.$now_date.$phone.".png";
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
	$email = urldecode($_G['gp_email']);
	$province = urldecode($_G['gp_province']);
	$city = urldecode($_G['gp_city']);
	$address = urldecode($_G['gp_address']);
	$postcode = urldecode($_G['gp_postcode']);
	$watch_date = urldecode($_G['gp_watch_date']);
	$is_owners = $_G['gp_is_owners'];
	$bwm_cars = urldecode($_G['gp_bwm_cars']);
	$buy_car_date = urldecode($_G['gp_buy_car_date']);
	$learn_channels = urldecode($_G['gp_learn_channels']);
	$is_contact = $_G['gp_is_contact'];
	$is_readed = $_G['gp_is_readed'];
	$bwm_addtime = time();
	if(empty($is_readed)){
		$is_readed = 1;
	}
	
	$erweima_path = erweima();
	echo $erweima_path;die;
    if(empty($erweima_path)) {
        api_json_result(1,1,"二维码生成失败",null);
    }
	
	$sql = "insert into tbl_bwm_game(qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime)";
	$sql .= " values('{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}')";
	
    $res = DB::query($sql);
	if($res == false){
		api_json_result(1,1,"提交失败",null);
	}
	
	$data['title']='erweima';
	$data['data']=$site_url.$erweima_path;
    api_json_result(1,0,"国内注册用户，我们将寄送门票到您注册邮寄地址，海外注册用户烦请您在比赛当日至BMW大师赛现场领取门票，每日限领2张。",$data);
}

?>
