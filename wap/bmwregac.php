<?php
/*
*
*	报名页面
*
*/
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();
$ac=$_G['gp_ac'];

 $provinceArray[1]="北京市";
        
    $provinceArray[2]="天津市";
        
    $provinceArray[3]="河北省";
        
    $provinceArray[4]="山西省";
        
    $provinceArray[5]="内蒙古自治区";
        
    $provinceArray[6]="辽宁省";
        
    $provinceArray[7]="吉林省";
        
    $provinceArray[8]="黑龙江省";
        
    $provinceArray[9]="上海市";
        
    $provinceArray[10]="江苏省";
        
    $provinceArray[11]="浙江省";
        
    $provinceArray[12]="安徽省";
        
    $provinceArray[13]="福建省";
        
    $provinceArray[14]="江西省";
        
    $provinceArray[15]="山东省";
        
    $provinceArray[16]="河南省";
        
    $provinceArray[17]="湖北省";
        
    $provinceArray[18]="湖南省";
        
    $provinceArray[19]="广东省";
        
    $provinceArray[20]="广西壮族自治区";
        
    $provinceArray[21]="海南省";
        
    $provinceArray[22]="重庆市";
        
    $provinceArray[23]="四川省";
        
    $provinceArray[24]="贵州省";
        
    $provinceArray[25]="云南省";
        
    $provinceArray[26]="西藏自治区";
        
    $provinceArray[27]="陕西省";
        
    $provinceArray[28]="甘肃省";
        
    $provinceArray[29]="青海省";
        
    $provinceArray[30]="宁夏回族自治区";
        
    $provinceArray[31]="新疆维吾尔自治区";
        
    $provinceArray[32]="台湾省";
        
    $provinceArray[33]="香港特别行政区";
        
    $provinceArray[34]="澳门特别行政区";
        
    $provinceArray[35]="海外";
        
    $provinceArray[36]="其他";


if($ac=="bwm_reg")
{ 
	$qiancheng = $_G['gp_qiancheng'];
	 
	$family_name = $_G['gp_family_name'];
	$name = $_G['gp_name'];	
	$year = $_G['gp_year'];
	$month = $_G['gp_month'];
	$day = $_G['gp_day']; 
	$phone = $_G['gp_phone'];
	$email = $_G['gp_email'];
	$prov = $_G['gp_province'];	
    $province=$provinceArray[$prov];
	$cty = $_G['gp_city']; 

	$address = $_G['gp_address'];
	$postcode = $_G['gp_postcode'];
	$watchdate = $_G['gp_watch_date']; 
	
	print_r($watchdate);exit;
	$watch_date=implode(",", $watchdate );
	//$watch_date=rtrim($watch_date, ",") ;
	$is_owners = $_G['gp_is_owners'];
	$bwm_cars = $_G['gp_bwm_cars']; 
	$buy_car_date = $_G['gp_buy_car_date'];	 
	$learn_channels = $_G['gp_learn_channels'];
	$is_contact = $_G['gp_is_contact']; 
	$is_readed = $_G['gp_is_readed'];	 
	$bwm_addtime = time();
	if(empty($is_readed)){
		$is_readed = 1;
	}
	$sql = "insert into tbl_bwm_game(qiancheng,family_name,name,year,month,day,phone,email,province,city,address,postcode,watch_date,is_owners,bwm_cars,buy_car_date,learn_channels,is_contact,is_readed,bwm_addtime)";
	$sql .= " values('{$qiancheng}','{$family_name}','{$name}','{$year}','{$month}','{$day}','{$phone}','{$email}','{$province}','{$city}','{$address}','{$postcode}','{$watch_date}','{$is_owners}','{$bwm_cars}','{$buy_car_date}','{$learn_channels}','{$is_contact}','{$is_readed}','{$bwm_addtime}')";
	
    $res = DB::query($sql);
	if($res == false){
	 	echo "<script>location='error.html';</script>";
	}

   	echo "<script>location='success.html';</script>";
}
 
?>
