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
 
//显示距标准杆
function Getchd($avcave) {
	 // $avcave=$avcave+1;
		if ($avcave > 0) {
			$avcave = '+' . $avcave;
		}
		if ($avcave == 0) {
			$avcave = 'E';
		} 
	
	return $avcave;
} 

function Getcss($cave_, $par) {
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
 
$sid = DB::result_first ( "select  sid from tbl_fenzhan  where  fenzhan_id='$fenzhan_id'" );
 
?>

<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title></title> 

<style type="text/css"> 
<!-- 
body,div,ul,li{ 
padding:0; 
text-align:center; 
} 
body{
	font:12px "宋体";
	text-align:center;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
} 
body, h1, h2 { font-family: "Trebuchet MS", Trebuchet, Verdana, Helvetica, Arial, sans-serif; }
a:link{ 
color:#00F; 
text-decoration:none; 
} 
a:visited { 
color: #00F; 
text-decoration:none; 
} 
a:hover { 
color: #c00; 
text-decoration:underline; 
} 
td{ text-align:center;}
ul{ list-style:none;} 
/*选项卡1*/ 
#Tab1{ 
width:<%?php echo $width;?%>px; 
margin:0px; 
padding:0px; 
margin:0 auto;} 
/*选项卡2*/ 
#Tab2{ 
width:576px; 
margin:0px; 
padding:0px; 
margin:0 auto;} 
/*菜单class*/ 
.Menubox { 
width:100%; 
background:url(http://www.jb51.net/upload/small/20079299441652.gif); 
height:28px; 
line-height:28px; 
} 
.Menubox ul{ 
margin:0px; 
padding:0px; 
} 
.Menubox li{ 
float:left; 
display:block; 
cursor:pointer; 
width:114px; 
text-align:center; 
color:#949694; 
font-weight:bold; 
} 
.Menubox li.hover{ 
padding:0px; 
background:#fff; 
width:116px; 
border-left:1px solid #A8C29F; 
border-top:1px solid #A8C29F; 
border-right:1px solid #A8C29F; 
background:url(http://www.jb51.net/upload/small/200792994426548.gif); 
color:#739242; 
font-weight:bold; 
height:27px; 
line-height:27px; 
} 
.Contentbox{ 
clear:both; 
margin-top:0px; 
border:1px solid #A8C29F; 
border-top:none; 
height:auto; 
text-align:center; 
padding-top:8px; 
} 
--> 
</style> 
<script> 
<!-- 
/*第一种形式 第二种形式 更换显示样式*/ 
function setTab(name,cursel,n){ 
for(i=1;i<=n;i++){ 
var menu=document.getElementById(name+i); 
var con=document.getElementById("con_"+name+"_"+i); 
menu.className=i==cursel?"hover":""; 
con.style.display=i==cursel?"block":"none"; 
} 
} 
//--> 
</script> 
</head> 
<body>

<table width="<?php echo $width;?>" align="left"><tr><td>
<table width="<?php echo $width;?>" align="center"><tr><td>
<img src="/nd/event/nd_img_<?php echo $sid;?>.jpg"  width="<?php echo $width;?>"  onerror="this.src='/images/nd/bwvip.jpg'" >
</td></tr></table>
<?php if($event_id){?>

<table width="<?php echo $width;?>" >
 <tr>
       <th>RND</th>
       <th>1</th>
       <th>2</th>
       <th>3</th>
       <th>4</th>
       <th>5</th>
       <th>6</th>
       <th>7</th>
       <th>8</th>
       <th>9</th>
       <th>OUT</th>
       <th>10</th>
       <th>11</th>
       <th>12</th>
       <th>13</th>
       <th>14</th>
       <th>15</th>
       <th>16</th>
       <th>17</th>
       <th>18</th>
       <th>IN</th>
       <th>PAR</th>
       <th>TOT</th>
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
       <td><?php echo $POUT;?></td> 
       <td><?php echo $par[9];?></td>
       <td><?php echo $par[10];?></td>
       <td><?php echo $par[11];?></td>
       <td><?php echo $par[12];?></td>
       <td><?php echo $par[13];?></td>
       <td><?php echo $par[14];?></td>
       <td><?php echo $par[15];?></td>
       <td><?php echo $par[16];?></td>
       <td><?php echo $par[17];?></td> 
       <td><?php echo $PIN;?></td>
       <td>&nbsp;</td> 
       <td><?php echo $PTL;?></td> 
  </tr>
  <?php  
  $t=1;
  $jdt=DB::query("SELECT uid,realname,lun,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,  (cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lto
,total_ju_par   FROM tbl_baofen WHERE event_id=".$event_id." and uid=".$uid." order by is_end desc,lun");  
while($lib=DB::fetch($jdt)){
	?>
 
     <tr>
       <td><?php echo $lib['lun'];?></td>
       <td  <?php echo  Getcss($lib['cave_1'], $par[0]);?> ><?php echo $lib['cave_1'];?></td>
       <td  <?php echo  Getcss($lib['cave_2'], $par[1]);?> ><?php echo $lib['cave_2'];?></td>
       <td  <?php echo  Getcss($lib['cave_3'], $par[2]);?> ><?php echo $lib['cave_3'];?></td>
       <td  <?php echo  Getcss($lib['cave_4'], $par[3]);?> ><?php echo $lib['cave_4'];?></td>
       <td  <?php echo  Getcss($lib['cave_5'], $par[4]);?> ><?php echo $lib['cave_5'];?></td>
       <td  <?php echo  Getcss($lib['cave_6'], $par[5]);?> ><?php echo $lib['cave_6'];?></td>
       <td  <?php echo  Getcss($lib['cave_7'], $par[6]);?> ><?php echo $lib['cave_7'];?></td>
       <td  <?php echo  Getcss($lib['cave_8'], $par[7]);?> ><?php echo $lib['cave_8'];?></td>
       <td  <?php echo  Getcss($lib['cave_9'], $par[8]);?> ><?php echo $lib['cave_9'];?></td>  
       <td><?php echo $lib['lout'];?></td>
       <td  <?php echo  Getcss($lib['cave_10'], $par[9]);?> ><?php echo $lib['cave_10'];?></td>
       <td  <?php echo  Getcss($lib['cave_11'], $par[10]);?> ><?php echo $lib['cave_11'];?></td>
       <td  <?php echo  Getcss($lib['cave_12'], $par[11]);?> ><?php echo $lib['cave_12'];?></td>
       <td  <?php echo  Getcss($lib['cave_13'], $par[12]);?> ><?php echo $lib['cave_13'];?></td>
       <td  <?php echo  Getcss($lib['cave_14'], $par[13]);?> ><?php echo $lib['cave_14'];?></td>
       <td  <?php echo  Getcss($lib['cave_15'], $par[14]);?> ><?php echo $lib['cave_15'];?></td>
       <td  <?php echo  Getcss($lib['cave_16'], $par[15]);?> ><?php echo $lib['cave_16'];?></td>
       <td  <?php echo  Getcss($lib['cave_17'], $par[16]);?> ><?php echo $lib['cave_17'];?></td>
       <td  <?php echo  Getcss($lib['cave_18'], $par[17]);?> ><?php echo $lib['cave_18'];?></td> 
       <td><?php echo $lib['lin'];?></td>
       <td><?php echo Getchd($lib['total_ju_par']);?></td>
       <td><?php echo $lib['lto'];?></td>
     </tr>
      
 

 <?php } ?>
 
   
</table>
  
<?php }else{
	//得到相同赛事不同轮次
$parent_id = DB::result_first ( "select  parent_id from tbl_fenzhan  where  fenzhan_id='$fenzhan_id'" );
if($parent_id){
		
	$sql="SELECT * FROM tbl_fenzhan WHERE parent_id=$parent_id or fenzhan_id=$parent_id  ORDER BY fenzhan_lun";
  }else
  {
	
	$sql="SELECT * FROM tbl_fenzhan WHERE parent_id=$fenzhan_id or fenzhan_id=$fenzhan_id  ORDER BY fenzhan_lun";
  } 
  
  
  $bf = DB::query($sql);
    while ($row = DB::fetch($bf)) {
        $list[] = $row;
		$i++;
		$x++;
    }
	?>

 
<table align="center">
<tr><td>
<div id="Tab1"> 
<div class="Menubox"> 
<ul> 
<?php
for ($j=1; $j<=$i; $j++) {
?>

<li id="one<?php echo $j;?>" onClick="setTab('one',<?php echo $j;?>,<?php echo $i+1;?>)" <?php if($j==1){?>class="hover"<?php }?>>rnd<?php echo $j;?></li>  

<?php }?>
<li id="one<?php echo $i+1;?>" onClick="setTab('one',<?php echo $i+1;?>,<?php echo $i+1;?>)" >total</li>  

</ul> 
</div> 
<div class="Contentbox"> 
 <?php
//$ab=0;
foreach($list as $key=>$k){
?>
<div id="con_one_<?php echo $key+1;?>" <?php if($key==0){?>class="hover"<?php }else{?>style="display:none"<?php }?> >
<table width="<?php echo $width;?>" >
  <?php   
  
  $i=0;//排名计数器
  $sql=" SELECT A.*,@rank:=@rank+1 as pm FROM ( select * from tbl_baofen where fenzhan_id=".$k['fenzhan_id']."  order by total_ju_par asc  ) A ,(SELECT @rank:=0) B;
";
   
 // $sql="SELECT * FROM tbl_baofen WHERE fenzhan_id=".$k['fenzhan_id']." order by is_end desc,total_ju_par";
  
  $jdt=DB::query($sql);  
		while($lib=DB::fetch($jdt)){
		
		$i++;  
		
		if($tt==$lib['total_ju_par'])
		{
			$j=$i;
		}
		else
		{
			
		$tt=$lib['total_ju_par'];
		}
		
	?>
  <tr> 
  <td><?php echo $lib['pm'];?></td>
  <td><a href="?event_id=<?php echo $lib['event_id'];?>&fenzhan_id=<?php echo $lib['fenzhan_id'];?>&uid=<?php echo $lib['uid'];?>" target="_blank"><?php echo $lib['realname'];?></a></td>
  <td><?php echo Getchd($lib['total_ju_par']);?></td>
  <td><?php echo $lib['total_score'];?><td></tr>

<?php } ?>
  
  </table>
  
  </div>
   
   
<?php } 

 $maxlun = DB::result_first ( " select  max(fenzhan_lun) from tbl_fenzhan where sid='".$sid."'" );  
 if($maxlun==1)
 {
  $sql=" SELECT A.*,A.total_ju_par+A.total_ju_par1 as jupar,A.total_score  as tlscore,  @rank:=@rank+1 as pm FROM ( select *,total_ju_paras jupar from tbl_baofen where sid='".$sid."' and lun=".$maxlun."   order by  jupar asc  ) A ,(SELECT @rank:=0) B;
";
 } 
 if($maxlun==2)
 {
  $sql=" SELECT A.*,A.total_ju_par+A.total_ju_par1 as jupar,A.total_score+A.total_score_lun1 as tlscore,  @rank:=@rank+1 as pm FROM ( select *,total_ju_par+total_ju_par1  as jupar from tbl_baofen where sid='".$sid."' and lun=".$maxlun."   order by  jupar asc  ) A ,(SELECT @rank:=0) B;
";
 }
 if($maxlun==3)
 {
  $sql=" SELECT A.*,A.total_ju_par+A.total_ju_par1+A.total_ju_par2 as jupar,A.total_score+A.total_score_lun1+A.total_score_lun2 as tlscore,  @rank:=@rank+1 as pm FROM ( select *,total_ju_par+total_ju_par1+total_ju_par2 as jupar from tbl_baofen where sid='".$sid."' and lun=".$maxlun."   order by  jupar asc  ) A ,(SELECT @rank:=0) B;
";
 }
 if($maxlun==4)
 {
  $sql=" SELECT A.*,A.total_ju_par+A.total_ju_par1+A.total_ju_par2+A.total_ju_par3 as jupar,A.total_score+A.total_score_lun1+A.total_score_lun2+A.total_score_lun3 as tlscore,  @rank:=@rank+1 as pm FROM ( select *,total_ju_par+total_ju_par1+total_ju_par2+total_ju_par3 as jupar from tbl_baofen where sid='".$sid."' and lun=".$maxlun."   order by  jupar asc  ) A ,(SELECT @rank:=0) B;
";
 }
?>
 <div id="con_one_<?php echo $x+1;?>" style="display:none" >
<table width="<?php echo $width;?>" >
  <?php   
    
  $jdt=DB::query($sql);  
		while($lib=DB::fetch($jdt)){
		
		$i++;  
		
		if($tt==$lib['total_ju_par'])
		{
			$j=$i;
		}
		else
		{
			
		$tt=$lib['total_ju_par'];
		}
		
	?>
  <tr> 
  <td><?php echo $lib['pm'];?></td>
  <td><a href="?event_id=<?php echo $lib['event_id'];?>&fenzhan_id=<?php echo $lib['fenzhan_id'];?>&uid=<?php echo $lib['uid'];?>" target="_blank"><?php echo $lib['realname'];?></a></td>
  <td><?php echo Getchd($lib['jupar']);?></td>
  <td><?php echo $lib['tlscore'];?><td></tr>

<?php } ?>
  
  </table> 
  </div> 
</div> 
</div>
</td>
</tr>
</table>

<?php }?>

</td>
</tr>
</table>
</body>