<?
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

$fenzhan_id = getgpc('fenzhan_id'); 
$event_id = getgpc('event_id'); 
$uid = getgpc('uid'); 
 //距标准杆
function Gpar($cave, $par) {
	$option = $cave - $par;
	 
	return $option;
}

//距标准杆
function Getpar($cave, $par) {
	$option = $cave - $par;
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



function Getteamname($team_id) {
	 $teamname = DB::result_first( " select `team_name` from " . DB::table ( "golf_team" ) . " where team_id=$team_id" );
	return $teamname;
}

function Getusername($uid) {
	 $username = DB::result_first( " select `username` from " . DB::table ( "common_member" ) . " where uid=$uid" );
	return $username;
}

//显示成绩
function Getchj($cave) {
    
		if ($cave > 0) {
			$cave =  $cave;
		}else{
			$cave = '-';
		}
	 
	
	return $cave;
}

//显示距标准杆
function Getchd($total_ju_par) {
	 // $total_ju_par=$total_ju_par+1;
		if ($total_ju_par > 0) {
			$total_ju_par = '+' . $total_ju_par;
		}
		if ($total_ju_par == 0) {
			$total_ju_par = 'E';
		} 
		if ($total_ju_par >200) {
			$total_ju_par = '-';
		} 
		
	
	return $total_ju_par;
}

//显示DQ RTD
function Getstat($total_score) {
	if ($total_score < 999)
		{$dataInfo = $total_score;}
	switch ($total_score) {
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
	   
		case 0 : 
			$dataInfo = "&nbsp;";
			break;
	}
	
	return $dataInfo;
}

function Getcss($cave_, $par) {
	if($cave>0){
	$option = $cave_ - $par;
	if ($cave_ == - 1) {
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#089218\"";
		
		$dataInfo = " style=\"text-align:center;color:#FFffff;\"";
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
				$dataInfo = " style=\"text-align:center;color:#000000;background:#e9e9e9\"";
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
			//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#FF00ff\"";
			$dataInfo = " style=\"text-align:center;color:#000000;\"";
	}
	}
	return $dataInfo;
} 
if($fenzhan_id){
	$qc_par_result = DB::fetch_first ( " select `fenzhan_a`,fenzhan_b from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' " );
	 
	$par = explode(',',$qc_par_result['fenzhan_a'].','.$qc_par_result['fenzhan_b'] );  
	
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
$dguoqi=960/1280;
$dguoqi1=$dguoqi*$width;
 
$lun = DB::result_first ( "select  fenzhan_lun from tbl_fenzhan  where  fenzhan_id='$fenzhan_id'" );
 $count = DB::result_first ( "select count(*) from tbl_baofen    where 1=1  and  fenzhan_id='".$fenzhan_id."'     order by  total_score asc" );
		
	
  $perpage = empty ( $_GET ['pg'] ) ? 13 : intval ( $_GET ['pg'] );
		$page = empty ( $_GET ['page'] ) ? 0 : intval ( $_GET ['page'] );
		if ($page < 1) {
			$page = 1;
		}
		
		$start = ($page - 1) * $perpage;
		//ckstart ( $start, $perpage );
		$pm = $_GET ['pm'] ? $_GET ['pm'] : 1; 
		 $pm = $pm > $count ? 1 : $pm;
		$realpages_x = @ceil ( $count / $perpage );
		if ($realpages_x <= $_GET ['page']) {
			$page = 1;
		} else {
			$page ++;
		}	
		
		if ($count) {
		 	$sql = "select cave_1  from tbl_baofen where  fenzhan_id='".$fenzhan_id."'  limit $start,$perpage";
		 
			$query = DB::query ( $sql );
			while ( $row = DB::fetch ( $query ) ) {
			$pm ++;
			}
		
		}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title> 大正高尔夫赛事成绩表</title>
</head>
<style type="text/css">
body { font: normal 20px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif; color: #4f6b72; background: #dae7f0;margin:0 auto ; }
a { color: #c75f3e; }
td {line-height:15px; border-right: 1px solid #ffffff; border-bottom: 1px solid #ffffff;  font-size:18px; padding: 2px 0; color: #4f6b72;  text-align:center; font-weight:600; }
td.alt { border-left: 1px solid #ffffff; border-top: 0; background:#d5e6f6; color: #797268; width:3%;height:20px;font:  16px;   font-weight:600; }
td.spec { border-left: 1px solid #ffffff; border-top: 0; background: #bdd3e8; font:  18px;  font-weight:600; }
td.specalt {border-left: 1px solid #ffffff; border-top: 0;  font:18px; color: #272727; text-align:center; font-weight:600; }
 /*---------for IE 5.x bug*/ html>body td{ font-size:14px;}

</style>
<META   HTTP-EQUIV=REFRESH   CONTENT="60;URL=show.php?fenzhan_id=<?php echo $_GET['fenzhan_id']?>&ac=tv&page=<?php echo $page;?>&pg=<?php echo $perpage;?>&pm=<?php echo $pm;?>" >
<body>
<table width="100%" border="0" align="center"  cellpadding="0" cellspacing="0" > 
  <tr>
    <td width="3%">rank</td>
    <td width="5%">Name</td>
    <td width="4%"><?php echo $par[0];?></td>
    <td width="4%"><?php echo $par[1];?></td>
    <td width="4%"><?php echo $par[2];?></td>
    <td width="4%"><?php echo $par[3];?></td>
    <td width="4%"><?php echo $par[4];?></td>
    <td width="4%"><?php echo $par[5];?></td>
    <td width="4%"><?php echo $par[6];?></td>
    <td width="4%"><?php echo $par[7];?></td>
    <td width="4%"><?php echo $par[8];?></td> 
    <td width="4%"><?php echo $POUT;?></td> 
    <td width="4%"><?php echo $par[9];?></td>
    <td width="4%"><?php echo $par[10];?></td>
    <td width="4%"><?php echo $par[11];?></td>
    <td width="4%"><?php echo $par[12];?></td>
    <td width="4%"><?php echo $par[13];?></td>
    <td width="4%"><?php echo $par[14];?></td>
    <td width="4%"><?php echo $par[15];?></td>
    <td width="4%"><?php echo $par[16];?></td>
    <td width="4%"><?php echo $par[17];?></td> 
    <td width="4%"><?php echo $PIN;?></td>
    <td width="4%">r<?php echo $lun;?></td> 
    <td width="4%">total</td> 
  </tr>
  <?php  
  $t=1;
		 
	
	$sql = "select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from tbl_baofen where 1=1 and  fenzhan_id='".$fenzhan_id."'   ORDER BY $orderby  status desc,total_ju_par,total_score,fenzu_id limit $start,$perpage";
 
  $jdt=DB::query($sql);  
  
  
while($lib=DB::fetch($jdt)){ 
		$lib ['pm'] = $pm ++;
		if($lib['status']==-4)
				{
					$lib['order']="CUT";
				}
	?>
 
     <tr>
       <td><?php if($lib['total_score']>200||$lib['total_score']==0){echo "-";}else{ echo ($lib['pm']-$perpage);}?></td>
       <td><?php echo $lib['realname'];?></td>
       <td  <?php echo  Getcss($lib['cave_1'], $par[0]);?> ><?php echo Getchj( $lib['cave_1']);?></td> 
       <td  <?php echo  Getcss($lib['cave_2'], $par[1]);?> ><?php echo Getchj( $lib['cave_2']);?></td> 
       <td  <?php echo  Getcss($lib['cave_3'], $par[2]);?> ><?php echo Getchj( $lib['cave_3']);?></td> 
       <td  <?php echo  Getcss($lib['cave_4'], $par[3]);?> ><?php echo Getchj( $lib['cave_4']);?></td> 
       <td  <?php echo  Getcss($lib['cave_5'], $par[4]);?> ><?php echo Getchj( $lib['cave_5']);?></td> 
       <td  <?php echo  Getcss($lib['cave_6'], $par[5]);?> ><?php echo Getchj( $lib['cave_6']);?></td> 
       <td  <?php echo  Getcss($lib['cave_7'], $par[6]);?> ><?php echo Getchj( $lib['cave_7']);?></td> 
       <td  <?php echo  Getcss($lib['cave_8'], $par[7]);?> ><?php echo Getchj( $lib['cave_8']);?></td> 
       <td  <?php echo  Getcss($lib['cave_9'], $par[8]);?> ><?php echo Getchj( $lib['cave_9']);?></td>  
       <td> <?php echo  Getchj( $lib['lout']);?></td> 
       <td  <?php echo  Getcss($lib['cave_10'], $par[9]);?> ><?php echo Getchj( $lib['cave_10']);?></td> 
       <td  <?php echo  Getcss($lib['cave_11'], $par[10]);?> ><?php echo Getchj( $lib['cave_11']);?></td> 
       <td  <?php echo  Getcss($lib['cave_12'], $par[11]);?> ><?php echo Getchj( $lib['cave_12']);?></td> 
       <td  <?php echo  Getcss($lib['cave_13'], $par[12]);?> ><?php echo Getchj( $lib['cave_13']);?></td> 
       <td  <?php echo  Getcss($lib['cave_14'], $par[13]);?> ><?php echo Getchj( $lib['cave_14']);?></td> 
       <td  <?php echo  Getcss($lib['cave_15'], $par[14]);?> ><?php echo Getchj( $lib['cave_15']);?></td> 
       <td  <?php echo  Getcss($lib['cave_16'], $par[15]);?> ><?php echo Getchj( $lib['cave_16']);?></td> 
       <td  <?php echo  Getcss($lib['cave_17'], $par[16]);?> ><?php echo Getchj( $lib['cave_17']);?></td> 
       <td  <?php echo  Getcss($lib['cave_18'], $par[17]);?> ><?php echo Getchj( $lib['cave_18']);?></td> 
       <td> <?php echo Getchj( $lib['lin']);?></td>
       <td><?php echo Getchd($lib['total_ju_par']);?></td>
       <td><?php if($lib['total_score']>200||$lib['total_score']==0){echo "-";}else{echo $lib['total_score'];}?></td>
     </tr>
      
 

 <?php } ?>
 
   
</table>
 <table width="98%" border="0" cellspacing="0" cellpadding="0" align="left">
   <tr>
     <td style="background:#dae7f0; " ><img src="/images/nd/ndbt.jpg" ></td>
   </tr>
 </table>
</body>
</html>