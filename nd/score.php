<?php

/**
 * [Discuz!] (C)2001-2099 Comsenz Inc.
 * This is NOT a freeware, use is subject to license terms
 *
 * $Id: home.php 22839 2011-05-25 08:05:18Z monkey $
 */ 
define ( 'APPTYPEID', 1 );
define ( 'CURSCRIPT', 'home' );

if (! empty ( $_GET ['mod'] ) && ($_GET ['mod'] == 'misc' || $_GET ['mod'] == 'invite')) {
	define ( 'ALLOWGUEST', 1 );
}

require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
  

$ac = $_GET ['ac'];
$do = $_GET ['do']; 
$nd_id = $_GET ['ndid']; 

//载入模板
$size = $_GET ['size'];
$width = $_GET ['width'];
if(!$width)
{
	$width=800;
}

//echo $width;

$cachelist = array ('magic', 'userapp', 'usergroups', 'diytemplatenamehome' );
$discuz->cachelist = $cachelist;
$discuz->init ();


//获取赛事ID
$score_info=DB::fetch_first("select uid,event_id,field_id,fenzhan_id,total_score,start_time,realname from tbl_baofen where baofen_id='".$nd_id."'  ");

$uid=$score_info['uid']; 
$event_id=$score_info['event_id'];
$field_id=$score_info['field_id']; 
$fenzhan_id=$score_info['fenzhan_id'];  
$zong_fen=$score_info['total_score'];
$realname=$score_info['realname'];
$event_name=DB::result_first("select event_name from tbl_event where event_id='".$event_id."' "); 

 
if($nd_id > 0)
{ 
	$ct= DB::result_first("SELECT lun  FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid."  order by lun desc limit 1");
	
	if($ct>1)
	{
	if($size!="small")
		{ 
 
  
if($fenzhan_id){
	if($event_id==27){
	$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b,sid from tbl_fenzhan where fenzhan_id='115' " );
	}else{
	$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b,sid from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );
	}
	$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] );  

     $sid=$qc_par_result['sid']; 	
  	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	}

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
body{
	font:<?php echo $dguoqi1;?>px "宋体";
	text-align:center;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
} 
td{padding:1px;}
 
 
td{text-align:center;font:<?php echo $dguoqi1;?>px "宋体";}
</style>   
</head> 
<body>
 
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>
<?php if(file_exists("/nd/event/nd_img_$sid.jpg")){?>
<img src="/nd/event/nd_img_<?php echo $sid;?>.jpg"  width="100%" >
<?php }else{?>
<img src="/images/nd/bwvip.jpg"  width="100%"   >
<?php }?>
</td></tr>
<tr>
    <td  style="line-height:25px;">姓名：<?php echo $realname;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 赛事：<?php echo $event_name;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </td>
    </tr>
<tr>
  <td  >&nbsp;</td>
</tr>
</table>
<?php if($event_id){?>

<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" >
 <tr>
       <td width="4%">RND</td>
       <td width="4%">1</td>
       <td width="4%">2</td>
       <td width="4%">3</td>
       <td width="4%">4</td>
       <td width="4%">5</td>
       <td width="4%">6</td>
       <td width="4%">7</td>
       <td width="4%">8</td>
       <td width="4%">9</td>
       <td width="4%">10</td>
       <td width="4%">11</td>
       <td width="4%">12</td>
       <td width="4%">13</td>
       <td width="4%">14</td>
       <td width="4%">15</td>
       <td width="4%">16</td>
       <td width="4%">17</td>
       <td width="4%">18</td> 
       <td width="4%">PAR</td>
       <td width="4%">TOT</td>
  </tr>
  <tr>
       <td>PAR</td>
       <td><?php echo $par[0];?></td>
       <td><?php echo $par[1];?></td>
       <td><?php echo $par[2];?></td>
       <td><?php echo $par[3];?></td>
       <td><?php echo $par[4];?></td>
       <td><?php echo $par[5];?></td>
       <td><?php echo $par[6];?></td>
       <td><?php echo $par[7];?></td>
       <td><?php echo $par[8];?></td>  
       <td><?php echo $par[9];?></td>
       <td><?php echo $par[10];?></td>
       <td><?php echo $par[11];?></td>
       <td><?php echo $par[12];?></td>
       <td><?php echo $par[13];?></td>
       <td><?php echo $par[14];?></td>
       <td><?php echo $par[15];?></td>
       <td><?php echo $par[16];?></td>
       <td><?php echo $par[17];?></td>  
       <td>&nbsp;</td> 
       <td><?php echo $PTL;?></td> 
  </tr>
  <?php  
  $t=1;
  if($event_id==27){
  $jdt=DB::query("SELECT uid,realname,lun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,  (cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lto
,total_ju_par   FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid." and fenzhan_id in(115,116,117) order by is_end desc,lun");  
  }else{
  $jdt=DB::query("SELECT uid,realname,lun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,  (cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lto
,total_ju_par   FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid."  order by is_end desc,lun");  
  }
while($lib=DB::fetch($jdt)){
	?>
 
     <tr>
       <td><?php echo $lib['lun'];?></td>
             <td  <?php echo  Getcss($lib['cave_1'], $par[0]);?> ><?php echo Getchj( $lib['cave_1']);?></td> 
       <td  <?php echo  Getcss($lib['cave_2'], $par[1]);?> ><?php echo Getchj( $lib['cave_2']);?></td> 
       <td  <?php echo  Getcss($lib['cave_3'], $par[2]);?> ><?php echo Getchj( $lib['cave_3']);?></td> 
       <td  <?php echo  Getcss($lib['cave_4'], $par[3]);?> ><?php echo Getchj( $lib['cave_4']);?></td> 
       <td  <?php echo  Getcss($lib['cave_5'], $par[4]);?> ><?php echo Getchj( $lib['cave_5']);?></td> 
       <td  <?php echo  Getcss($lib['cave_6'], $par[5]);?> ><?php echo Getchj( $lib['cave_6']);?></td> 
       <td  <?php echo  Getcss($lib['cave_7'], $par[6]);?> ><?php echo Getchj( $lib['cave_7']);?></td> 
       <td  <?php echo  Getcss($lib['cave_8'], $par[7]);?> ><?php echo Getchj( $lib['cave_8']);?></td> 
       <td  <?php echo  Getcss($lib['cave_9'], $par[8]);?> ><?php echo Getchj( $lib['cave_9']);?></td>      
        <td  <?php echo  Getcss($lib['cave_10'], $par[9]);?> ><?php echo Getchj( $lib['cave_10']);?></td> 
       <td  <?php echo  Getcss($lib['cave_11'], $par[10]);?> ><?php echo Getchj( $lib['cave_11']);?></td> 
       <td  <?php echo  Getcss($lib['cave_12'], $par[11]);?> ><?php echo Getchj( $lib['cave_12']);?></td> 
       <td  <?php echo  Getcss($lib['cave_13'], $par[12]);?> ><?php echo Getchj( $lib['cave_13']);?></td> 
       <td  <?php echo  Getcss($lib['cave_14'], $par[13]);?> ><?php echo Getchj( $lib['cave_14']);?></td> 
       <td  <?php echo  Getcss($lib['cave_15'], $par[14]);?> ><?php echo Getchj( $lib['cave_15']);?></td> 
       <td  <?php echo  Getcss($lib['cave_16'], $par[15]);?> ><?php echo Getchj( $lib['cave_16']);?></td> 
       <td  <?php echo  Getcss($lib['cave_17'], $par[16]);?> ><?php echo Getchj( $lib['cave_17']);?></td> 
       <td  <?php echo  Getcss($lib['cave_18'], $par[17]);?> ><?php echo Getchj( $lib['cave_18']);?></td>  
       <td><?php echo Getchd($lib['total_ju_par']);?></td>
       <td><?php if($lib['lto']){echo $lib['lto'];}else{echo '-';}?></td>
     </tr>
      
 

 <?php } ?>
 
   
</table>

<div style="float:left;"><img src="/images/nd/grbf.gif" width="332" height="28" />
</div>
<?php }?>
 
</body>

<?php	
exit;
   }
   }
}

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


 

if($nd_id > 0)
{
	if($size=="small")
	{
		include_once template("diy:nd/ndscore_small"); 
	}
	else
	{
		include_once template("diy:nd/ndscore"); 
	}
	
}
else
{	
	include_once template("diy:nd/ndscore1"); 
}

?>