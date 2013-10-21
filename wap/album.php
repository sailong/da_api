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
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title></title> 

<style type="text/css">  
body,div,ul,li{ 
padding:0; 
text-align:center; 
} 
img{width:90%}
td{padding:1px;}
 
 
td{text-align:center;font:<?php echo $dguoqi1;?>px "宋体";}
</style>   
</head> 
<body>
 
  
<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" >
  
  <?php  
  $t=1;
  $jdt=DB::query("SELECT *   FROM  pre_home_pic WHERE albumid=".$id." ");  
while($lib=DB::fetch($jdt)){
	?>
 
     <tr>
       <td align="center"><img src="http://www.bwvip.com/data/attachment/album/<?php echo $lib['filepath'];?>"></td> 
     </tr>     
     <tr>
       <td align="center"><?php echo $lib['title'];?></td> 
     </tr> 
 

 <?php } ?>
 
   
</table>
  
 
</body>

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