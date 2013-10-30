<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


/**
 * 团队赛成绩直播
 * 
 */


$ac=$_G['gp_ac'];
//分站ID
$fenz_id =  ! empty ( $_GET ['fenz_id'] ) ? $_GET ['fenz_id'] : 29;
//赛事ID
$sid=$_G['gp_sid'];
if(!$sid)
{
	$sid = 3803973;
}

//球场id
$qc_id = 3803491;

if($fenz_id==30) 
{
	$onlymark=1565264560;
}else
{
	$onlymark=379021388;
}


//球场标准杆
$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
$par = explode ( ',', $qc_par_result ['par'] );  
$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
$PTL = $POUT + $PIN;


$strwh=$strwh.' and onlymark='.$onlymark; 		


//国家
$country_array = array('3803491' => 'china','948' => 'china','1178' => 'china','1195' => 'china','1035' => 'china','1013' => 'china','1187' => 'china','1212' => 'china','1210' => 'china','1150' => 'china','3804025' => 'korea','3804026' => 'korea','3804027' => 'korea','3804028' => 'korea','3804029' => 'korea','3804031' => 'korea','3804032' => 'korea','3804033' => 'korea','3804034' => 'korea','3804035' => 'korea');

//球员所在球队
$dyarray = array('3803491' => '孙喜丰|许大军','948' => '陈树新|李源梧','1178' => '陈焕成|Herry Chi','1195' => '陈明刚|左坤','1035' => '吴立国|冯四栋','1013' => '梁启生|梁杰坤','1187' => '孙红星|许韫','1212' => '雷盛生|胡险峰','1210' => '陈冠中|张磊','1150' => '杨坚|张义','3804025' => '柳在烈|杨津石','3804026' => '赵泰丰|黄奎碗','3804027' => '金仁兼|申永植','3804028' => '李锺变|张振焕','3804029' => '宋仁石|崔宝贤','3804031' => '余永奎|李奎焕','3804032' => '朴敬挤|崔文石','3804033' => '闵敬曹|咸明英','3804034' => '姜峰石|李准基','3804035' => '魏强福|张治元');
//国旗
$qzarray = array('3803491' => 'big-guoqi.png','948' => 'big-guoqi.png','1178' => 'big-guoqi.png','1195' => 'big-guoqi.png','1035' => 'big-guoqi.png','1013' => 'big-guoqi.png','1187' => 'big-guoqi.png','1212' => 'big-guoqi.png','1210' => 'big-guoqi.png','1150' => 'big-guoqi.png','3804025' => 'big-hanguo.png','3804026' => 'big-hanguo.png','3804027' => 'big-hanguo.png','3804028' => 'big-hanguo.png','3804029' => 'big-hanguo.png','3804031' => 'big-hanguo.png','3804032' => 'big-hanguo.png','3804033' => 'big-hanguo.png','3804034' => 'big-hanguo.png','3804035' => 'big-hanguo.png');
//颜色
$ysarray = array('3803491' => '#f00','948' => '#f00','1178' => '#f00','1195' => '#f00','1035' => '#f00','1013' => '#f00','1187' => '#f00','1212' => '#f00','1210' => '#f00','1150' => '#f00','3804025' => '#3a9de2','3804026' => '#3a9de2','3804027' => '#3a9de2','3804028' => '#3a9de2','3804029' => '#3a9de2','3804031' => '#3a9de2','3804032' => '#3a9de2','3804033' => '#3a9de2','3804034' => '#3a9de2','3804035' => '#3a9de2');
//积分
$jfarray = array('3803491' => '1','948' => '1','1178' => '1','1195' => '0','1035' => '0','1013' => '1','1187' => '1','1212' => '0','1210' => '0','1150' => '0','3804025' => '0','3804026' => '1','3804027' => '0','3804028' => '1','3804029' => '0','3804031' => '1','3804032' => '0','3804033' => '0','3804034' => '1','3804035' => '1');

 
$size = $_GET ['size'];
$order = $_GET ['order'];
$width = $_GET ['width'];
$width1 = $_GET ['width']-54;
$width2 = $_GET ['width']-120;


if(!$width)
{
	$width=720;
}
 
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
$dy4=18/720;
$sb4=$dy4*$width; 

$st1=80/720;
$sg1=$st1*$width; 

$st2=60/720;
$sg2=$st2*$width; 
$st3=90/720;
$sg3=$st3*$width; 

  

//获取赛事ID
$score_info=DB::fetch_first("select uid,sid,tlcave,start_time,realname from ".DB::table("golf_nd_baofen")." where nd_id='".$nd_id."'  ");
$event_uid=$score_info['sid'];
$zong_fen=$score_info['tlcave'];
$realname=$score_info['realname'];
$event_name=DB::result_first("select realname from ".DB::table("common_member_profile")." where uid='".$event_uid."' ");
$addtime=date("Y-m-d G:i:s",$score_info['start_time']);


//用户组，模板调用
$uid = ! empty ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;
$getstat = array ();
$getstat = getusrarry ( $uid );
$orderby=" team_num asc,isout desc";
if($order=="1")
{ 
	$strwh=$strwh." and isend=1 ";
}
else
{
	$orderby=" isend desc,ttlcave,lin,cave_18,cave_17,cave_16";
}

/*
$sql="select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,tlcave+tlcave1 as ttlcave from " . DB::table ( 'golf_nd_baofen' ) . " where 1=1 $strwh ORDER BY $orderby"; 
$bf = DB::query ($sql); 
while($row=DB::fetch($bf))
{
	$nblist[] = $row;
} 
$pm = 1;
if($nblist)
{
	foreach ( $nblist as $key => $value )
	{
		$nblist [$key] ['pm'] = $pm ++;
		$nblist [$key] ['chj'] = $nblist [$key] ['tlcave'] - $PTL; 
	}
}
*/



//竖版
if($ac=="shuban")
{
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 and event_uid='".$sid."' order by event_addtime desc limit 1 ");
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']='中国';
	$list_info['event_left_intro']='CHINA';
	$list_info['event_left_pic']=$site_url."/nd/images/big-guoqi.png";
	$list_info['event_right']='韩国';
	$list_info['event_right_intro']='SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url."/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	$strwh=$strwh." and isend=1 ";
	
	$sql="select nd_id as baofen_id,sid,fenz_id as fenzhan_id,uid,realname,tlcave,start_time,team_num,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,tlcave+tlcave1 as ttlcave from " .DB::table ( 'golf_nd_baofen' ) . " where 1=1 $strwh ORDER BY $orderby "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['start_time']=date("Y年m月d日",$row['start_time']);
		$row['country']=$country_array[$row['uid']];
		$nblist[]=array_default_value($row,array('duiyuan'));
	} 
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
			$nblist[$key]['chj'] = $nblist [$key] ['tlcave'] - $PTL; 
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//坚版 SMALL
if($ac=="small")
{

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 and event_uid='".$sid."' order by event_addtime desc limit 1 ");
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']='中国';
	$list_info['event_left_intro']='CHINA';
	$list_info['event_left_pic']=$site_url."/nd/images/big-guoqi.png";
	$list_info['event_right']='韩国';
	$list_info['event_right_intro']='SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url."/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	$strwh=$strwh." and isend=1 ";
	
	$sql="select team_num,tlcave,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,tlcave+tlcave1 as ttlcave from ".DB::table('golf_nd_baofen')." where 1=1 $strwh group by team_num ORDER BY team_num asc "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$sub_list=DB::query("select nd_id as baofen_id,sid,fenz_id as fenzhan_id,uid,realname,tlcave,start_time,team_num,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,tlcave+tlcave1 as ttlcave from ".DB::table('golf_nd_baofen')." where 1=1 and team_num='".$row['team_num']."' $strwh order by team_name asc ");
		$row_sub=array();
		while($row_sub_arr=DB::fetch($sub_list))
		{
			$row_sub_arr['start_time']=date("Y年m月d日",$row_sub_arr['start_time']);
			$row_sub_arr['duiyuan']=str_replace("|",",",$dyarray[$row_sub_arr['uid']]);
			$row_sub_arr['country']=$country_array[$row_sub_arr['uid']];
		
			$row_sub[]=array_default_value($row_sub_arr);
		}
		
		
		//$row['sub_member']=(array_sort_by_field($row_sub,'guoqi',true));
		
		
		if($row_sub[0]['tlcave'] < $row_sub[1]['tlcave'])
		{
			$row['win']='left';
			$row_sub[0]['defen']=1;
			$row_sub[1]['defen']=0;
			
			//$row['left_score']=$row['left_score']+1;
		}
		else if($row_sub[0]['tlcave'] = $row_sub[1]['tlcave'])
		{
			if($row_sub[0]['ttlcave']<$row_sub[1]['ttlcave'])
			{
				$row['win']='left';
				$row_sub[0]['defen']=1;
				$row_sub[1]['defen']=0;
			}
			else
			{
				$row['win']='right';
				$row_sub[0]['defen']=0;
				$row_sub[1]['defen']=1;
			}
		}
		else
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
		}
		
		$list_info['event_left_score']=(string)($list_info['event_left_score']+$row_sub[0]['defen']);
		$list_info['event_right_score']=(string)($list_info['event_right_score']+$row_sub[1]['defen']);
		
		$row['sub_member']=$row_sub;
		
		

		$nblist[]=array_default_value($row,array('duiyuan','sub_member'));
	} 
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
			$nblist[$key]['chj'] = $nblist[$key]['tlcave']-$PTL; 
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//横版 heng
if($ac=="heng")
{
	//$strwh=$strwh." and isend=1 ";
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 and event_uid='".$sid."' order by event_addtime desc limit 1 ");
	
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']='中国';
	$list_info['event_left_intro']='CHINA';
	
	$list_info['event_left_pic']=$site_url."/nd/images/big-guoqi.png";
	
	$list_info['event_right']='韩国';
	$list_info['event_right_intro']='SOUTH KOREA';
	
	$list_info['event_right_pic']=$site_url."/nd/images/big-hanguo.png";
	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	
	$sql="select team_num,tlcave from ".DB::table('golf_nd_baofen')." where 1=1 $strwh group by team_num ORDER BY team_num asc "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['par']=$par;
		
		$sub_list=DB::query("select nd_id as baofen_id,sid,fenz_id as fenzhan_id,uid,realname,tlcave,start_time,team_num,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,tlcave+tlcave1 as ttlcave from ".DB::table('golf_nd_baofen')." where 1=1 and team_num='".$row['team_num']."' $strwh order by team_name asc ");
		$row_sub=array();
		$i=0;
		while($row_sub_arr=DB::fetch($sub_list))
		{
			
			$row_sub_arr['start_time']=date("Y年m月d日",$row_sub_arr['start_time']);
			$row_sub_arr['duiyuan']=str_replace("|",",",$dyarray[$row_sub_arr['uid']]);
			$row_sub_arr['country']=$country_array[$row_sub_arr['uid']];
			
			$row_sub_arr['chj'] = $row_sub_arr['tlcave']-$PTL; 
			//$row_sub_arr['defen'] = (string)1;
			
			$row_sub_arr['color_1']=get_dong_color($row_sub_arr['cave_1'],$par[0]);
			$row_sub_arr['color_2']=get_dong_color($row_sub_arr['cave_2'],$par[1]);
			$row_sub_arr['color_3']=get_dong_color($row_sub_arr['cave_3'],$par[2]);
			$row_sub_arr['color_4']=get_dong_color($row_sub_arr['cave_4'],$par[3]);
			$row_sub_arr['color_5']=get_dong_color($row_sub_arr['cave_5'],$par[4]);
			$row_sub_arr['color_6']=get_dong_color($row_sub_arr['cave_6'],$par[5]);
			$row_sub_arr['color_7']=get_dong_color($row_sub_arr['cave_7'],$par[6]);
			$row_sub_arr['color_8']=get_dong_color($row_sub_arr['cave_8'],$par[7]);
			$row_sub_arr['color_9']=get_dong_color($row_sub_arr['cave_9'],$par[8]);
			$row_sub_arr['color_10']=get_dong_color($row_sub_arr['cave_10'],$par[9]);
			$row_sub_arr['color_11']=get_dong_color($row_sub_arr['cave_11'],$par[10]);
			$row_sub_arr['color_12']=get_dong_color($row_sub_arr['cave_12'],$par[11]);
			$row_sub_arr['color_13']=get_dong_color($row_sub_arr['cave_13'],$par[12]);
			$row_sub_arr['color_14']=get_dong_color($row_sub_arr['cave_14'],$par[13]);
			$row_sub_arr['color_15']=get_dong_color($row_sub_arr['cave_15'],$par[14]);
			$row_sub_arr['color_16']=get_dong_color($row_sub_arr['cave_16'],$par[15]);
			$row_sub_arr['color_17']=get_dong_color($row_sub_arr['cave_17'],$par[16]);
			$row_sub_arr['color_18']=get_dong_color($row_sub_arr['cave_18'],$par[17]);
			
			$row_sub[]=array_default_value($row_sub_arr,array('par'));
			$i++;
		}
		
		if($row_sub[0]['tlcave'] < $row_sub[1]['tlcave'])
		{
			$row['win']='left';
			$row_sub[0]['defen']=1;
			$row_sub[1]['defen']=0;
			
			//$row['left_score']=$row['left_score']+1;
		}
		else if($row_sub[0]['tlcave'] = $row_sub[1]['tlcave'])
		{
			if($row_sub[0]['ttlcave']<$row_sub[1]['ttlcave'])
			{
				$row['win']='left';
				$row_sub[0]['defen']=1;
				$row_sub[1]['defen']=0;
			}
			else
			{
				$row['win']='right';
				$row_sub[0]['defen']=0;
				$row_sub[1]['defen']=1;
			}
		}
		else
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
		}
		
		$list_info['event_left_score']=(string)($list_info['event_left_score']+$row_sub[0]['defen']);
		$list_info['event_right_score']=(string)($list_info['event_right_score']+$row_sub[1]['defen']);
		
		
		$row['sub_member']=$row_sub;

		$nblist[]=array_default_value($row,array('duiyuan','sub_member'));
	} 
	$pm = 1;
	if($nblist)
	{
		foreach($nblist as $key => $value )
		{
			$nblist[$key]['pm'] = $pm ++;
		}
	}	
	
	$data['title']='list_data';
	$data['data']=array(
		'list_info'=>$list_info,
		'list'=>$nblist
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


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

function Getdefen($score1,$score2,$isend)
{
	if($isend==1&&$score1<200&&$score2<200)
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
 
function Getwin($score1,$score2,$isend,$type)
{
	
	if($isend==1&&$score1<200&&$score2<200)
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


 
function get_dong_color($score,$par)
{
	if($score-$par==3)
	{
		$color=1;
	}
	else if($score-$par==2)
	{
		$color=2;
	}
	else if($score-$par==1)
	{
		$color=3;
	}
	else if($score-$par==0)
	{
		$color=4;
	}
	else if($score-$par==-1)
	{
		$color=5;
	}
	else if($score-$par==-2)
	{
		$color=6;
	}
	else if($score-$par==-3)
	{
		$color=7;
	}
	else
	{
		$color=0;
	}
	
	return $color;
}
	

function sort_by_country($a,$b)
{
	
	if ($a == $b) return 0;
	return ($a > $b) ? -1 : 1;

}	

?>