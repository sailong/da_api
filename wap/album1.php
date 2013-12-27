<?php

/**
 * [Discuz!] (C)2001-2099 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 *
 * $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */ 
define ( 'APPTYPEID', 1 );
define ( 'CURSCRIPT', 'home' );

 

require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
  
 
$id = $_GET ['id']; 


$discuz->cachelist = $cachelist;
$discuz->init ();

$width = $_GET ['width'];
if(!$width)
{
	$width=960;
} 
  //横版缩放
 $agent = strtolower($_SERVER['HTTP_USER_AGENT']); 
    $iphone = (strpos($agent, 'iphone')) ? true : false; 
    $ipad = (strpos($agent, 'ipad')) ? true : false; 
    $android = (strpos($agent, 'android')) ? true : false; 
    if($iphone || $ipad)
    {    	
       $dguoqi=35/960;
    } else{
    	$dguoqi=12/960;
    }
   
 
$dguoqi1=$dguoqi*$width;

 
  
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="MobileOptimized" content="236"/>
<meta http-equiv="Cache-Control" content="no-cache"/>
<title><?php echo $title_name; ?></title>
<style type="text/css">
body{margin:0;padding:0;font-size:12pt;color:#000000; line-height:150%; font-family:"微软雅黑","宋体";}
p,h2,h3,h4,ul,li{margin:0;padding:0;list-style:none;}
img{margin:0;padding:0;border:none;width:100%;}
.tuijian a:link,.tuijian a:visited{text-decoration:none; font-size:12pt;color:#000000;}
.tuijian a:hover{text-decoration:none;color:000;}
.wrap{width:100%;height:auto;}
h3{width:95%;font-size:18px;line-height:25px; height:60px; background:url(images/line-bg.png) no-repeat left bottom;margin:20px 0 10px 0;}
h2{width:95%;margin:20px auto 0;font-size:18px;height:40px;line-height:25px;background:url(images/line-bg.png) no-repeat left bottom;}
.tuijian{width:100%;line-height:30px;margin: 20px auto 10px;}
.tuijian .tu{width:13px;float:left;padding-top:10px;margin-right:10px;}
.bottom{width:100%;margin:40px auto;}

</style>
</head>
<body>
 
<body>
<div class="wrap">
	<div class="top"><img src="images/album_img/dazheng-top.png" /></div>
    <div style="padding:0 20px;">
<div class="content">
	<h3><?php echo $title_name; ?></h3><br />
  
  <?php  
  $t=1;
  $jdt=DB::query("SELECT *   FROM  pre_home_pic WHERE albumid=".$id." ");  
while($lib=DB::fetch($jdt)){
	?>
	<h4><img src="http://www.bwvip.com/data/attachment/album/<?php echo $lib['filepath'];?>"></h4>
	<p style="margin-bottom:50px;font-weight:bold; font-size:18px;"><?php echo $lib['title'];?></p>

 <?php } ?>

 <?php
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8";
	
	
}
else if(strpos($userAgent,"Android"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	
}
if($dz_down_url){
?>

		
<div class="center"><a href="<? echo $dz_down_url; ?>"><img src="images/album_img/jinru-button.png" /></a></div>

<?php } ?>
  
 
</div>
</body>
</html>

<?php	
exit;  

$addtime=date("Y-m-d G:i:s",$score_info['start_time']);

 

//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;
 
	$getstat = array ();
	$getstat = getusrarry ( $uid );
	//$gropid=$getstat['groupid']; 
if($nd_id > 0)
{
 $bf = DB::query ( "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin   from tbl_baofen  where  baofen_id=".$nd_id );}else{	
		if ($uid > 0) {
		
		 $bf = DB::query ( "select  *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin   from tbl_baofen where sid='$sid'  and field_id='$field_id' and uid=".$uid );
		}else{
		 $bf = DB::query ( "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from tbl_baofen  ORDER BY    total_ju_par,lin"); 
		}
 }
while ( $row = DB::fetch ( $bf ) ) {
	$fz [] = $row;
} 
  
	

     asort($new_cg);
 
	$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b,sid from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );

	 
	$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] ); 
	 
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
$sid=$qc_par_result['sid']; 

define ( 'CURMODULE', $mod );

runhooks ();
//距标准杆
function Gpar($score, $par) {
	$option = $score - $par;
	 
	return $option;
}

//距标准杆
function Getpar($score, $par)
{
	$option = $score - $par;
	if ($option == 0) {
		$dataInfo = "E";
	}
	if ($option > 0) {
		$dataInfo = "+" . $option;
	}
	if ($option < 0) {
		$dataInfo = $option;
	}
	return $dataInfo;
}

//当前洞的成绩
function getscore($nd_id,$dong) {
	  
	 $sql=" select `cave_$dong` from tbl_baofen where nd_id='$nd_id' " ; 
	$cave= DB::result_first($sql);
 
	 
	if ($cave) {
		$dataInfo = $cave;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo; 
}

 
//显示距标准杆
function Getchd($avscore)
{
	 // $avscore=$avscore+1;
		if ($avscore > 0) {
			$avscore = '+' . $avscore;
		}
		if ($avscore == 0) {
			$avscore = 'E';
		} 
	if ($avscore == 1000) {
			$avscore = '-';
		}
	return $avscore;
}

//显示DQ RTD
function Getstat($tlscore)
{
	switch ($tlscore) {
		//弃权
		case 999 :
			$dataInfo = "Quit";			
			//$dataInfo = "";
			break;
		//DQ
		case 1000 :
			$dataInfo = "DQ";			
			//$dataInfo = "";
			break;
		//取消
		case 1001 :
			$dataInfo = "RTD";
			//$dataInfo = "";
			break;
	
	}
	if ($tlscore < 999)
		$dataInfo = $tlscore;
	return $dataInfo;
}

function Getchj($score)
{
	if($score){
		$dataInfo=$score;
	}else
    {
		$dataInfo='-';
	}
	
	return $dataInfo;
}

function Getcss($score, $par)
{
	if($score){
	$option = $score - $par;
	if ($score == - 1) {
		$dataInfo = " style=\"text-align:center;color:#FFffff;background:#089218\"";
	} else {
		switch ($option) {
			//成绩数据显示
			

			//低于标准杆3杆以上或者一杆进洞的：字变白色 底色变为深黄色，
			case - 3 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#fd6804\"";
				break;
			//低于标准杆两杆：字变白色 底变为ffcc01
			case - 2 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#ffcc01\"";
				break;
			
			//低于标准杆1杆：字变白色
			case - 1 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#cd3301\"";
				break;
			
			//平标准杆：字变白色 没背景
			case 0 :
				$dataInfo = " style=\"text-align:center;color:#000000;\"";
				break;
			
			//高于标准杆1杆：字变白色 
			case 1 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#5dcff1\"";
				break;
			
			//高于标准杆2杆：字变成白色 底为正常蓝色
			case 2 :
				$dataInfo = " style=\"text-align:center;color:#FFffff;background:#0166ff\"";
				break;
			
		//高于标准杆3杆以上的：字是白色 底为深蓝色
		//case 3 :
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		//	break;
		

		}
		if ($option >= 3)
			$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		if ($option < - 3)
			$dataInfo = " style=\"text-align:center;color:#FFffff;background:#FF00ff\"";
	}
	} 
	
	return $dataInfo;
}


 
?>