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
require_once '../source/class/class_core.php';
require_once '../source/function/function_home.php';

$discuz = & discuz_core::instance ();
 
//分站ID
$fenz_id = 29;
//赛事ID
$sid = 3803973; 
//球场id
$qc_id = 3803491;  
	if($_GET['onlymark']){
		$onlymark=$_GET['onlymark'];
		}else
		{
		$onlymark=1565264560;
		};
		
 $strwh=$strwh.' and onlymark='.$onlymark; 
		

//球员所在球队
$dyarray = array('3803491' => '孙喜丰|许大军','948' => '陈树新|李源梧','1178' => '陈焕成|Herry Chi','1195' => '陈明刚|左坤','1035' => '吴立国|冯四栋','1013' => '梁启生|梁杰坤','1187' => '孙红星|许韫','1212' => '雷盛生|胡险峰','1210' => '陈冠中|张磊','1150' => '杨坚|张义','3804025' => '柳在烈|杨津石','3804026' => '赵泰丰|黄奎碗','3804027' => '金仁兼|申永植','3804028' => '李锺变|张振焕','3804029' => '宋仁石|崔宝贤','3804031' => '余永奎|李奎焕','3804032' => '朴敬挤|崔文石','3804033' => '闵敬曹|咸明英','3804034' => '姜峰石|李准基','3804035' => '魏强福|张治元');
$qzarray = array('3803491' => 'small-guoqi.png','948' => 'small-guoqi.png','1178' => 'small-guoqi.png','1195' => 'small-guoqi.png','1035' => 'small-guoqi.png','1013' => 'small-guoqi.png','1187' => 'small-guoqi.png','1212' => 'small-guoqi.png','1210' => 'small-guoqi.png','1150' => 'small-guoqi.png','3804025' => 'small-hanguo.png','3804026' => 'small-hanguo.png','3804027' => 'small-hanguo.png','3804028' => 'small-hanguo.png','3804029' => 'small-hanguo.png','3804031' => 'small-hanguo.png','3804032' => 'small-hanguo.png','3804033' => 'small-hanguo.png','3804034' => 'small-hanguo.png','3804035' => 'small-hanguo.png');
$ysarray = array('3803491' => '#f00','948' => '#f00','1178' => '#f00','1195' => '#f00','1035' => '#f00','1013' => '#f00','1187' => '#f00','1212' => '#f00','1210' => '#f00','1150' => '#f00','3804025' => '#3a9de2','3804026' => '#3a9de2','3804027' => '#3a9de2','3804028' => '#3a9de2','3804029' => '#3a9de2','3804031' => '#3a9de2','3804032' => '#3a9de2','3804033' => '#3a9de2','3804034' => '#3a9de2','3804035' => '#3a9de2');
$zuarray = array('3803491' => 'cnzu','948' => 'cnzu','1178' => 'cnzu','1195' => 'cnzu','1035' => 'cnzu','1013' => 'cnzu','1187' => 'cnzu','1212' => 'cnzu','1210' => 'cnzu','1150' => 'cnzu','3804025' => 'krzu','3804026' => 'krzu','3804027' => 'krzu','3804028' => 'krzu','3804029' => 'krzu','3804031' => 'krzu','3804032' => 'krzu','3804033' => 'krzu','3804034' => 'krzu','3804035' => 'krzu');


 
$size = $_GET ['size'];
$order = $_GET ['order'];
$width = $_GET ['width'];
$ty = $_GET ['ty'];
if(!$width)
{
	$width=720;
}
 
$widtht=676/720; 
$width1 =$widtht*$width;
 
 //横版缩放
$dguoqi=86/1280;
$dguoqi1=$dguoqi*$width;
 
$zhongjian=185/1280;
$zhongjian1=$zhongjian*$width;
 
$xguoqi=42/1280;
$xguoqi1=$xguoqi*$width;
 
 
$zt2=18/1280;
$hb1=$zt2*$width;
$zt2=30/1280;
$hb2=$zt2*$width;
$zt3=28/1280;
$hb2=$zt3*$width;
$zt4=20/1280;
$hb3=$zt4*$width;

//排名缩放

$dbut=217/720;
$dbut1=$dbut*$width; 
 
$xguoqi=42/720;
$xguoqi1=$xguoqi*$width;

$szhongjian=185/720;
$szhongjian1=$szhongjian*$width;
$wfenz=167/720;
$wfenz1=$wfenz*$width;
$wfenh=73/720;
$wfenz2=$wfenh*$width;
 
$xing=28/720;
$xing1=$xing*$width; 
 
$dy1=16/720;
$sb1=$dy1*$width; 
$dy2=30/720;
$sb2=$dy2*$width; 
$dy3=28/720;
$sb3=$dy3*$width; 

  
 
$cachelist = array ('magic', 'userapp', 'usergroups', 'diytemplatenamehome' );
$discuz->cachelist = $cachelist;
$discuz->init ();


//获取赛事ID
$score_info=DB::fetch_first("select uid,sid,tlcave,start_time,realname from ".DB::table("golf_nd_baofen")." where nd_id='".$nd_id."'  ");
$event_uid=$score_info['sid'];
$zong_fen=$score_info['tlcave'];
$realname=$score_info['realname'];
$event_name=DB::result_first("select realname from ".DB::table("common_member_profile")." where uid='".$event_uid."' ");
$addtime=date("Y-m-d G:i:s",$score_info['start_time']);

 
		

$pagesize = 4;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);
$theurl = 'zhscore.php';

//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;
 
	$getstat = array ();
	$getstat = getusrarry ( $uid );
	//$gropid=$getstat['groupid']; 
 $orderby=" team_num asc";
if($order=="1"){$orderby=" isend desc,avcave asc";}
 
$count = DB::result(DB::query("select count(*)  from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 $strwh ORDER BY  $orderby"));

if($count) {		
$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin  from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 $strwh ORDER BY  $orderby limit $start, $pagesize";
	 
$bf = DB::query ($sql ); 
		while ( $row = DB::fetch ( $bf ) ) {
			$nblist [] = $row;
		} 
		$pm = 1;
		if ($nblist) {
			foreach ( $nblist as $key => $value ) {
				$nblist [$key] ['pm'] = $pm ++;
				$nblist [$key] ['chj'] = $nblist [$key] ['tlcave'] - $PTL; 
			}
		}
}
$multi = multi($count, $pagesize, $page, $theurl); 
 

$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );

$par = explode ( ',', $qc_par_result ['par'] );  
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;



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
function getscore($uid,$sais_id,$qc_id,$dong)
{
	 $sql=" select  `cave_$dong`  from " . DB::table ( "golf_nd_baofen" ) . " where uid=$uid and sais_id=$sais_id  and fieldid=$qc_id " ;
	$score = DB::result_first($sql);
 
	 
	if ($score) {
		$dataInfo = $score;	 
	
	} else {
		$dataInfo = 0;
	}
	
	return $dataInfo;
}


//显示DQ RTD
function Getstat($tlcave) {
	if ($tlcave < 999)
		{$dataInfo = $tlcave;}
	switch ($tlcave) {
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

//显示成绩
function Getchj($uid,$sais_id,$qc_id)
{
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid=$qc_id" );
	
	$par = explode ( ',', $qc_par_result ['par'] );
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	$sql = "select  *  from " . DB::table ( 'golf_nd_baofen' ) . " where uid=$uid and sais_id=$sais_id and fieldid=$qc_id ";
	$query = DB::query ( $sql );
	$row = DB::fetch ( $query );
	if ($row ['score1'] > 0 && $row ['score2'] > 0 && $row ['score3'] > 0 && $row ['score4'] > 0 && $row ['score5'] > 0 && $row ['score6'] > 0 && $row ['score7'] > 0 && $row ['score8'] > 0 && $row ['score9'] > 0 && $row ['score10'] > 0 && $row ['score11'] > 0 && $row ['score12'] > 0 && $row ['score13'] > 0 && $row ['score14'] > 0 && $row ['score15'] > 0 && $row ['score16'] > 0 && $row ['score17'] > 0 && $row ['score18'] > 0) {
		$dataInfo = $row ['tlscore'] - $PTL;
		if ($dataInfo > 0) {
			$dataInfo = '+' . $dataInfo;
		}
		if ($dataInfo == 0) {
			$dataInfo = 'E';
		}
	
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
	
	return $avscore;
}
 
function Getuname($str,$type)
{
	if($type==1){
	 $dataInfo=str_replace("|"," ","$str");
	 }
	 else
	 {
	 $dataInfo=str_replace("|","<br>","$str");
	 }
	return $dataInfo;
}

function Getcss($score, $par)
{
	$option = $score - $par;
	if ($score == - 1) {
		$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#089218\"";
	} else {
		switch ($option) {
			//成绩数据显示
			

			//低于标准杆3杆以上或者一杆进洞的：字变白色 底色变为深黄色，
			case - 3 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#fd6804\"";
				break;
			//低于标准杆两杆：字变白色 底变为ffcc01
			case - 2 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#ffcc01\"";
				break;
			
			//低于标准杆1杆：字变白色
			case - 1 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#cd3301\"";
				break;
			
			//平标准杆：字变白色 没背景
			case 0 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#000000;\"";
				break;
			
			//高于标准杆1杆：字变白色 
			case 1 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#5dcff1\"";
				break;
			
			//高于标准杆2杆：字变成白色 底为正常蓝色
			case 2 :
				$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#0166ff\"";
				break;
			
		//高于标准杆3杆以上的：字是白色 底为深蓝色
		//case 3 :
		//$dataInfo = " style=\"text-align:center;color:#FFffff;background:#000033\"";
		//	break;
		

		}
		if ($option >= 3)
			$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#000033\"";
		if ($option < - 3)
			$dataInfo = " style=\"padding:2px 5px;text-align:center;color:#FFffff;background:#FF00ff\"";
	}
	return $dataInfo;
}

function Getdefen($score1,$score2)
{
	if($score1>60&&$score2>60&&$score1<200&&$score2<200)
	 {
		 $option=$score1-$score2;
		if($option<0)
		 {
		 $dataInfo=1;
		 }
		 if($option>0)
		 {
		 $dataInfo=0;
		 }
		 if($option==0)
		 {
		 $dataInfo=0.5;
		 }
	 }else{
		  $dataInfo=" ";
	 }
	 
	return $dataInfo;
}
 
function Getwin($score1,$score2,$type)
{
	
	if($score1>60&&$score2>60&&$score1<200&&$score2<200)
	 {
		 $option=$score1-$score2;		 
	
		 $data['wz']="win";
		if($option<0)
		 {
		$data['bg']="but-red-bg.png";
		 }
		 if($option>0)
		 {
		 $data['bg']="but-blue-bg.png";
		 }
		 if($option==0)
		 {
		 $data['wz']="AS";
		 $data['bg']="but-gray-bg.png";
		 }
	 }else{
		 $data['wz']="AS";
		 $data['bg']="but-gray-bg.png";
	 }
	 if($type==1){
		$dataInfo=$data['wz'];
		 }
	 if($type==2){
		$dataInfo=$data['bg'];
		 }
	return $dataInfo;
}
 

function Getteamname($team_id)
{
	 $teamname = DB::result_first( " select `team_name` from " . DB::table ( "golf_team" ) . " where team_id=$team_id" );
	return $teamname;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src='../static/js/jquery.js'></script>
<title>中韩挑战赛</title>
<style type="text/css">
body {
margin-left: 0px;
margin-top: 0px;
margin-right: 0px;
margin-bottom: 0px;
}
</style>
</head>

<body>
<table width="<?php echo $width;?>" border="0">
	  <tr style="background:#f4f4f4;">
	    <td>
        	<table width="<?php echo $width;?>" border="0" cellpadding="0" cellspacing="0" style="background:#f4f4f4; font-size:<?php echo $hb1;?>px;">
            	<tr>
                	<td colspan="21" align="center" style="background:url(images/nav-bg1.png) repeat-x;"><table>
                	  <tr>
                	    <td colspan="2"><img src="images/big-guoqi.png" width="<?php $dguoqi1;?>"/></td>
                	    <td colspan="4" style="text-align:center;font-size:<?php echo $hb3;?>px;">中国<br />
                	      CHINA</td>
                	    <td colspan="2"><table  style="background:url(images/nav-bg.png) repeat-x;">
                	      <tr >
                	        <td style="font-size:<?php echo $hb2;?>px;color:#6a6a6a;font-weight:bold;"><span id="zgscore"></span>&nbsp;1/2</td>
                	        <td ><img src="images/guoqi.png"  width="<?php $zhongjian1;?>"/></td>
                	        <td  style="font-size:<?php echo $hb2;?>px;color:#6a6a6a;font-weight:bold;"><span id="hgscore"></span>&nbsp;1/2</td>
              	        </tr>
              	      </table></td>
                	    <td colspan="3" style="text-align:left;font-size:<?php echo $hb3;?>px;">韩国<br />
                	       SOUTH KOREA</td>
                	    <td colspan="10" align="left"><img src="images/big-hanguo.png"  width="<?php $dguoqi1;?>"/></td>
              	    </tr>
              	  </table></td>
                </tr>
<?php for ($i=0; $i<=2; $i=$i+2) {?>
                 <tr style="background:#dadada;height:42px;">
                	<td colspan="1" align="center" cellpadding="0" cellspacing="0">第<?php echo $page*2+($i/2-1);?>组</td>
                    <td colspan="5" align="right" cellpadding="0" cellspacing="0"><img src="images/<?php echo $qzarray[$nblist[$i]['uid']];?>"  width=<?php echo $xguoqi1;?>"/></td>
                    <td colspan="5"><?php echo $nblist[$i]['realname'];?></td>
                    <td colspan="1" style="color:#565656;text-align:center" cellpadding="0" cellspacing="0">VS</td>
                    <td colspan="1"><img src="images/<?php echo $qzarray[$nblist[$i+1]['uid']];?>" width="<?php $xguoqi1;?>"/></td>
                    <td colspan="9" ><?php echo $nblist[$i+1]['realname'];?></td>
                </tr>
                   <tr style="height:50px;font-size:<?php echo$hb3;?>px;" align="center">
                	<td align="center" style="color:#767676;" width="12%">球洞</td>
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
                    <td width="4%">ALL</td>
                    <td width="5%">得分</td> 
                </tr>
                <tr style="height:50px;font-size:<?php echo $hb3;?>px;" align="center">
                	<td align="center" style="color:#767676;">标准杆</td>
                    <?php for ($j=0; $j<=17; $j++) {?>
                    <td><?php echo $par[$j];?></td>
                   
                    <?php }?>
                    <td>72</td>
                    <td></td> 
                </tr>
              <tr style="height:50px;font-size:<?php echo $hb4;?>px;" align="left">
                	<td  style="vertical-align:middle;align:left;padding-left:10px;"><table><tr><td><img src="images/<?php echo $qzarray[$nblist[$i]['uid']];?>" width="<?php $xguoqi1;?>"/></td> <td><?php echo Getuname($dyarray[$nblist[$i]['uid']],2);?></td></tr></table></td>
                	<td <?php if($nblist[$i]['cave_1']!=0){echo Getcss($nblist[$i]['cave_1'],$par[0]);}?>><?php if($nblist[$i]['cave_1']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_1'];}?></td>
    <td <?php if($nblist[$i]['cave_2']!=0){echo Getcss($nblist[$i]['cave_2'],$par[1]);}?>><?php if($nblist[$i]['cave_2']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_2'];}?></td>
    <td <?php if($nblist[$i]['cave_3']!=0){echo Getcss($nblist[$i]['cave_3'],$par[2]);}?>><?php if($nblist[$i]['cave_3']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_3'];}?></td>
    <td <?php if($nblist[$i]['cave_4']!=0){echo Getcss($nblist[$i]['cave_4'],$par[3]);}?>><?php if($nblist[$i]['cave_4']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_4'];}?></td>
    <td <?php if($nblist[$i]['cave_5']!=0){echo Getcss($nblist[$i]['cave_5'],$par[4]);}?>><?php if($nblist[$i]['cave_5']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_5'];}?></td>
    <td <?php if($nblist[$i]['cave_6']!=0){echo Getcss($nblist[$i]['cave_6'],$par[5]);}?>><?php if($nblist[$i]['cave_6']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_6'];}?></td>
    <td <?php if($nblist[$i]['cave_7']!=0){echo Getcss($nblist[$i]['cave_7'],$par[6]);}?>><?php if($nblist[$i]['cave_7']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_7'];}?></td>
    <td <?php if($nblist[$i]['cave_8']!=0){echo Getcss($nblist[$i]['cave_8'],$par[7]);}?>><?php if($nblist[$i]['cave_8']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_8'];}?></td>
    <td <?php if($nblist[$i]['cave_9']!=0){echo Getcss($nblist[$i]['cave_9'],$par[8]);}?>><?php if($nblist[$i]['cave_9']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_9'];}?></td> 
    <td <?php if($nblist[$i]['cave_10']!=0){echo Getcss($nblist[$i]['cave_10'],$par[9]);}?>><?php if($nblist[$i]['cave_10']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_10'];}?></td>
    <td <?php if($nblist[$i]['cave_11']!=0){echo Getcss($nblist[$i]['cave_11'],$par[10]);}?>><?php if($nblist[$i]['cave_11']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_11'];}?></td>
    <td <?php if($nblist[$i]['cave_12']!=0){echo Getcss($nblist[$i]['cave_12'],$par[11]);}?>><?php if($nblist[$i]['cave_12']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_12'];}?></td>
    <td <?php if($nblist[$i]['cave_13']!=0){echo Getcss($nblist[$i]['cave_13'],$par[12]);}?>><?php if($nblist[$i]['cave_13']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_13'];}?></td>
    <td <?php if($nblist[$i]['cave_14']!=0){echo Getcss($nblist[$i]['cave_14'],$par[13]);}?>><?php if($nblist[$i]['cave_14']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_14'];}?></td>
    <td <?php if($nblist[$i]['cave_15']!=0){echo Getcss($nblist[$i]['cave_15'],$par[14]);}?>><?php if($nblist[$i]['cave_15']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_15'];}?></td>
    <td <?php if($nblist[$i]['cave_16']!=0){echo Getcss($nblist[$i]['cave_16'],$par[15]);}?>><?php if($nblist[$i]['cave_16']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_16'];}?></td>
    <td <?php if($nblist[$i]['cave_17']!=0){echo Getcss($nblist[$i]['cave_17'],$par[16]);}?>><?php if($nblist[$i]['cave_17']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_17'];}?></td>
    <td <?php if($nblist[$i]['cave_18']!=0){echo Getcss($nblist[$i]['cave_18'],$par[17]);}?>><?php if($nblist[$i]['cave_18']<=0){?>&nbsp;<?php }else{ echo $nblist[$i]['cave_18'];}?></td>
    <td align="center"><?php echo Getstat($nblist[$i]['tlcave']);?></td>
<td  align="center" id="nd<?php echo $nblist[$i]['uid'];?>"><?php echo Getdefen($nblist[$i]['tlcave'],$nblist[$i+1]['tlcave']);?></td> 
                </tr>
 
              <tr style="height:50px;font-size:<?php echo $hb4;?>px;" align="left">
                	<td  style="vertical-align:middle;align:left;padding-left:10px;"><table><tr><td><img src="images/<?php echo $qzarray[$nblist[$i+1]['uid']];?>" width="<?php $xguoqi1;?>"/></td> <td><?php echo Getuname($dyarray[$nblist[$i+1]['uid']],2);?></td></tr></table></td>
                	<td <?php if($nblist[$i+1]['cave_1']!=0){echo Getcss($nblist[$i+1]['cave_1'],$par[0]);}?>><?php if($nblist[$i+1]['cave_1']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_1'];}?></td>
    <td <?php if($nblist[$i+1]['cave_2']!=0){echo Getcss($nblist[$i+1]['cave_2'],$par[1]);}?>><?php if($nblist[$i+1]['cave_2']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_2'];}?></td>
    <td <?php if($nblist[$i+1]['cave_3']!=0){echo Getcss($nblist[$i+1]['cave_3'],$par[2]);}?>><?php if($nblist[$i+1]['cave_3']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_3'];}?></td>
    <td <?php if($nblist[$i+1]['cave_4']!=0){echo Getcss($nblist[$i+1]['cave_4'],$par[3]);}?>><?php if($nblist[$i+1]['cave_4']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_4'];}?></td>
    <td <?php if($nblist[$i+1]['cave_5']!=0){echo Getcss($nblist[$i+1]['cave_5'],$par[4]);}?>><?php if($nblist[$i+1]['cave_5']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_5'];}?></td>
    <td <?php if($nblist[$i+1]['cave_6']!=0){echo Getcss($nblist[$i+1]['cave_6'],$par[5]);}?>><?php if($nblist[$i+1]['cave_6']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_6'];}?></td>
    <td <?php if($nblist[$i+1]['cave_7']!=0){echo Getcss($nblist[$i+1]['cave_7'],$par[6]);}?>><?php if($nblist[$i+1]['cave_7']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_7'];}?></td>
    <td <?php if($nblist[$i+1]['cave_8']!=0){echo Getcss($nblist[$i+1]['cave_8'],$par[7]);}?>><?php if($nblist[$i+1]['cave_8']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_8'];}?></td>
    <td <?php if($nblist[$i+1]['cave_9']!=0){echo Getcss($nblist[$i+1]['cave_9'],$par[8]);}?>><?php if($nblist[$i+1]['cave_9']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_9'];}?></td> 
    <td <?php if($nblist[$i+1]['cave_10']!=0){echo Getcss($nblist[$i+1]['cave_10'],$par[9]);}?>><?php if($nblist[$i+1]['cave_10']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_10'];}?></td>
    <td <?php if($nblist[$i+1]['cave_11']!=0){echo Getcss($nblist[$i+1]['cave_11'],$par[10]);}?>><?php if($nblist[$i+1]['cave_11']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_11'];}?></td>
    <td <?php if($nblist[$i+1]['cave_12']!=0){echo Getcss($nblist[$i+1]['cave_12'],$par[11]);}?>><?php if($nblist[$i+1]['cave_12']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_12'];}?></td>
    <td <?php if($nblist[$i+1]['cave_13']!=0){echo Getcss($nblist[$i+1]['cave_13'],$par[12]);}?>><?php if($nblist[$i+1]['cave_13']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_13'];}?></td>
    <td <?php if($nblist[$i+1]['cave_14']!=0){echo Getcss($nblist[$i+1]['cave_14'],$par[13]);}?>><?php if($nblist[$i+1]['cave_14']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_14'];}?></td>
    <td <?php if($nblist[$i+1]['cave_15']!=0){echo Getcss($nblist[$i+1]['cave_15'],$par[14]);}?>><?php if($nblist[$i+1]['cave_15']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_15'];}?></td>
    <td <?php if($nblist[$i+1]['cave_16']!=0){echo Getcss($nblist[$i+1]['cave_16'],$par[15]);}?>><?php if($nblist[$i+1]['cave_16']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_16'];}?></td>
    <td <?php if($nblist[$i+1]['cave_17']!=0){echo Getcss($nblist[$i+1]['cave_17'],$par[16]);}?>><?php if($nblist[$i+1]['cave_17']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_17'];}?></td>
    <td <?php if($nblist[$i+1]['cave_18']!=0){echo Getcss($nblist[$i+1]['cave_18'],$par[17]);}?>><?php if($nblist[$i+1]['cave_18']<=0){?>&nbsp;<?php }else{ echo $nblist[$i+1]['cave_18'];}?></td>
    <td align="center"><?php echo Getstat($nblist[$i+1]['tlcave']);?></td>
<td  align="center" id="nd<?php echo $nblist[$i+1]['uid'];?>"><?php echo Getdefen($nblist[$i+1]['tlcave'],$nblist[$i]['tlcave']);?></td> 
                </tr>
    </tr>
        <tr><td height="45">
        </td>
  </tr>               
<?php
}?>
 
                
           
                
            </table>
        </td>
     
  
</table>
<script>

 var nd3804025 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804025').html()) : parseFloat(0);
 var nd3804026 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804026').html()) : parseFloat(0);
 var nd3804027 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804027').html()) : parseFloat(0);
 var nd3804028 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804028').html()) : parseFloat(0);
 var nd3804029 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804029').html()) : parseFloat(0);
 var nd3804031 = parseFloat(jQuery('#nd3804025').html()) ? parseFloat(jQuery('#nd3804031').html()) : parseFloat(0);
 var nd3804032 = parseFloat(jQuery('#nd3804032').html()) ? parseFloat(jQuery('#nd3804032').html()) : parseFloat(0);
 var nd3804033 = parseFloat(jQuery('#nd3804033').html()) ? parseFloat(jQuery('#nd3804033').html()) : parseFloat(0);
 var nd3804034 = parseFloat(jQuery('#nd3804034').html()) ? parseFloat(jQuery('#nd3804034').html()) : parseFloat(0);
 var nd3804035 = parseFloat(jQuery('#nd3804035').html()) ? parseFloat(jQuery('#nd3804035').html()) : parseFloat(0);
 
var hgscore=nd3804025+nd3804026+nd3804027+nd3804028+nd3804029+nd3804031+nd3804032+nd3804033+nd3804034+nd3804035;
 
 var nd3803491 = parseFloat(jQuery('#nd3803491').html()) ? parseFloat(jQuery('#nd3803491').html()) : parseFloat(0);
 var nd948 = parseFloat(jQuery('#nd948').html()) ? parseFloat(jQuery('#nd948').html()) : parseFloat(0);
 var nd1178 = parseFloat(jQuery('#nd1178').html()) ? parseFloat(jQuery('#nd1178').html()) : parseFloat(0);
 var nd1195 = parseFloat(jQuery('#nd1195').html()) ? parseFloat(jQuery('#nd1195').html()) : parseFloat(0);
 var nd1035 = parseFloat(jQuery('#nd1035').html()) ? parseFloat(jQuery('#nd1035').html()) : parseFloat(0);
 var nd1013 = parseFloat(jQuery('#nd1013').html()) ? parseFloat(jQuery('#nd1013').html()) : parseFloat(0);
 var nd1187 = parseFloat(jQuery('#nd1187').html()) ? parseFloat(jQuery('#nd1187').html()) : parseFloat(0);
 var nd1212 = parseFloat(jQuery('#nd1212').html()) ? parseFloat(jQuery('#nd1212').html()) : parseFloat(0);
 var nd1210 = parseFloat(jQuery('#nd1210').html()) ? parseFloat(jQuery('#nd1210').html()) : parseFloat(0);
 var nd1150 = parseFloat(jQuery('#nd1150').html()) ? parseFloat(jQuery('#nd1150').html()) : parseFloat(0); 
 
var zgscore=nd3803491+nd948+nd1178+nd1195+nd1035+nd1013+nd1187+nd1212+nd1210+nd1150;
 jQuery('#zgscore').html(zgscore);
 jQuery('#hgscore').html(hgscore);
</script>
</body>
</html>