<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


/**
 * 团队赛成绩直播
 * 
 */


$ac=$_G['gp_ac'];
//赛事ID

$orderby=" fenzu_id asc,is_out desc";
if($order=="1")
{ 
	$strwh=$strwh." and is_end=1 ";
}
else
{
	$orderby=" is_end desc,ttlcave,lin,cave_18,cave_17,cave_16";
}





//横版 heng
if($ac=="heng")
{
	$event_id=$_G['gp_event_id'];
	$fenzhan_id=$_G['gp_fenzhan_id'];

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_left,event_left_flag,event_left_intro,event_left_pic,event_left_ico,event_right,event_right_flag,event_right_intro,event_right_pic,event_right_ico,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime,event_team_level from tbl_event where 1=1 and event_id='".$event_id."' order by event_addtime desc limit 1 ");
	
	$list_info['event_name']=$event_info['event_name'];
	$list_info['event_left']=$event_info['event_left'];//'中国';
	$list_info['event_left_intro']=$event_info['event_left_intro'];//'CHINA';
	
	if($event_info['event_left_pic'])
	{
		$list_info['event_left_pic']=$site_url.'/'.$event_info['event_left_pic'];
	}
	if($event_info['event_left_ico'])
	{
		$list_info['event_left_ico']=$site_url.'/'.$event_info['event_left_ico'];
	}
	
	$list_info['event_right']=$event_info['event_right'];//'韩国';
	$list_info['event_right_intro']=$event_info['event_right_intro'];//'SOUTH KOREA';
	if($event_info['event_right_pic'])
	{
		$list_info['event_right_pic']=$site_url.'/'.$event_info['event_right_pic'];
	}
	if($event_info['event_right_ico'])
	{
		$list_info['event_right_ico']=$site_url.'/'.$event_info['event_right_ico'];
	}

	$list_info['event_logo']=$site_url."/".$event_info['event_logo'];
	$list_info['event_logo_info']=getimagesize($list_info['event_logo']);
	
	if($fenzhan_id)
	{
		$big_where .=" and fenzhan_id='".$fenzhan_id."' ";
	}
	else
	{
		if($event_info['event_fenzhan_id'])
		{
			$big_where .=" and fenzhan_id='".$event_info['event_fenzhan_id']."' ";
		}
	}
	
	
	$fenzhan_list=DB::query("select fenzhan_id,fenzhan_lun from tbl_fenzhan where 1=1 and event_id='".$event_id."' order by fenzhan_lun asc  ");
	while($row_fenzhan=DB::fetch($fenzhan_list))
	{
		$list_info['fenzhan_list'][]=array_default_value($row_fenzhan);
	}
	
	

	
	$fenzhan_info=DB::fetch_first("select * from tbl_fenzhan where 1=1 ".$big_where." limit 1 ");
	$fenzhan_rule=$fenzhan_info['fenzhan_rule'];
	$list_info['fenzhan_lun']=$fenzhan_info['fenzhan_lun'];

	//球场标准杆
	$qc_par_result = DB::fetch_first ( " select `par` from " . DB::table ( "common_field" ) . " where uid='$qc_id'" );
	$par = explode( ',',$fenzhan_info['fenzhan_a'].",".$fenzhan_info['fenzhan_b'] );  
	$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
	$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
	$PTL = $POUT + $PIN;
	
	//取分组列表
	$sql="select fenzu_id from tbl_baofen where 1=1 $big_where group by fenzu_id ORDER BY fenzu_id asc "; 
	$bf = DB::query ($sql); 
	while($row=DB::fetch($bf))
	{
		$row['par']=$par;
		$row['team_num']=$row['fenzu_id'];
		

		//取成员
		if($fenzhan_rule==11)
		{
			//个人对抗
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc  ");
			
		}
		else if($fenzhan_rule==42)
		{
			//4人2球
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by event_user_team desc  ");
			
		}
		else if($fenzhan_rule==44)
		{
			//4人4球
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where group by event_user_team order by event_user_team desc  ");
		}
		else
		{
			//其他
			$sub_list=DB::query("select baofen_id,sid,event_apply_id,fenzhan_id,uid,realname,total_ju_par,total_ju_par as tlcave,start_time,fenzu_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin,total_ju_par+total_ju_par1 as ttlcave,event_user_id,event_user_team,event_apply_parent_id from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where group by event_user_team order by event_user_team desc  ");
		}
		
		
		$row_sub=array();
		$duiyuan = array();
		while($row_sub_arr=DB::fetch($sub_list))
		{
			
			//两个人
			$event_apply_info=$row_sub_arr;
			
			//获取队员姓名
			if($fenzhan_rule==11)
			{
				//个人对抗
				if($event_apply_info['event_apply_parent_id']>0)
				{
					$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");

					$user_sub_list=DB::query("select realname as event_apply_realname from tbl_baofen where 1=1 and event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' and fenzu_id='".$event_apply_info['fenzu_id']."' ");
					$user_sub_list_array=array();
					while($user_sub_list_arr=DB::fetch($user_sub_list))
					{
						$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
					}
					$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
				}
				else
				{
					$user_sub_list_array=array($event_apply_info['event_apply_realname']);
					$row_sub_arr['realname']="";
				}
			}
			else if($fenzhan_rule==42)
			{
				//4人2球
				$row_sub_arr['realname']=$event_apply_info['event_apply_realname'];
				
				$user_sub_list=DB::query("select realname as event_apply_realname from tbl_baofen where 1=1 and event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' and fenzu_id='".$event_apply_info['fenzu_id']."' ");
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				if(count($user_sub_list_array)<1)
				{
					$user_sub_list_array=array();
				}
				
			}
			else if($fenzhan_rule==44)
			{
				//4人4球
				$event_apply_info_parent=DB::fetch_first("select event_apply_realname from tbl_event_apply where event_apply_id='".$event_apply_info['event_apply_parent_id']."' ");
				$user_sub_list=DB::query("select realname as event_apply_realname from tbl_baofen where 1=1 and event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."' and fenzu_id='".$event_apply_info['fenzu_id']."' ");
				$user_sub_list_array=array();
				while($user_sub_list_arr=DB::fetch($user_sub_list))
				{
					$user_sub_list_array[]=$user_sub_list_arr['event_apply_realname'];
				}
				$row_sub_arr['realname']=$event_apply_info_parent['event_apply_realname'];
			}
			else
			{
				$user_sub_list_array=array($event_apply_info['event_apply_realname']);
				$row_sub_arr['realname']="";
				
			}
		
			$row_sub_arr['start_time']=date("Y年m月d日",$row_sub_arr['start_time']);
			$row_sub_arr['country']=$row_sub_arr['event_user_team'];
			$row_sub_arr['duiyuan']=implode(",",$user_sub_list_array);
		
			if($fenzhan_rule==44)
			{
				$sub_score_list_1=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'  and fenzu_id='".$event_apply_info['fenzu_id']."' order by baofen_id desc limit 1 ");
				
				$sub_score_list_2=DB::fetch_first("select baofen_id,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18 from tbl_baofen where event_apply_parent_id='".$event_apply_info['event_apply_parent_id']."' and fenzhan_id='".$fenzhan_info['fenzhan_id']."'   and fenzu_id='".$event_apply_info['fenzu_id']."' order by baofen_id desc limit 1 ");
				$sub_score_list=get_score_44($sub_score_list_1,$sub_score_list_2);
		
				
				//处理18洞成绩
				for($i=0; $i<18; $i++)
				{
					$d=$i+1;
					$row_sub_arr['cave_'.$d]=$sub_score_list[$i];
					
				}
				
			}
			else
			{
				$sub_score_list=array($row_sub_arr['cave_1'],$row_sub_arr['cave_2'],$row_sub_arr['cave_3'],$row_sub_arr['cave_4'],$row_sub_arr['cave_5'],$row_sub_arr['cave_6'],$row_sub_arr['cave_7'],$row_sub_arr['cave_8'],$row_sub_arr['cave_0'],$row_sub_arr['cave_10'],$row_sub_arr['cave_11'],$row_sub_arr['cave_12'],$row_sub_arr['cave_13'],$row_sub_arr['cave_14'],$row_sub_arr['cave_15'],$row_sub_arr['cave_16'],$row_sub_arr['cave_17'],$row_sub_arr['cave_18']);
				
			}
			
			$row_sub_arr['be_num']=score_count($row_sub_arr,'be');
			
			//去除多余字段
			unset($row_sub_arr['event_user_team']);
			unset($row_sub_arr['event_apply_parent_id']);
			
			
			$row_sub[]=array_default_value($row_sub_arr,array('par','be_num'));
			$i++;
		}
		
		
		//统计UP得分
		for($i=0; $i<18; $i++)
		{
			$d=$i+1;
			$row_sub[0]['up_total']=get_up_44($row_sub[0],$row_sub[1],$d,0,'up_total');
			$row_sub[1]['up_total']=get_up_44($row_sub[0],$row_sub[1],$d,1,'up_total');
			
			$row_sub[0]['up_'.$d]=get_up_44($row_sub[0],$row_sub[1],$d,0,'up_text');
			$row_sub[1]['up_'.$d]=get_up_44($row_sub[0],$row_sub[1],$d,1,'up_text');
		}
		
		
		//$row['status_ico']="";
		
		if($row_sub[0]['up_total'] > $row_sub[1]['up_total'])
		{
			$row['win']='left';
			$row_sub[0]['defen']=1;
			$row_sub[1]['defen']=0;
	
		}
		else if($row_sub[0]['up_total'] < $row_sub[1]['up_total'])
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
	
		}
		else if($row_sub[0]['up_total'] == $row_sub[1]['up_total'])
		{
			$row['win']='as';
			$row_sub[0]['defen']=0.5;
			$row_sub[1]['defen']=0.5;
		}
		else
		{
			$row['win']='right';
			$row_sub[0]['defen']=0;
			$row_sub[1]['defen']=1;
		}
		
		$row_sub[0]['defen']=(string)$row_sub[0]['defen'];
		$row_sub[1]['defen']=(string)$row_sub[1]['defen'];
		
		
		$list_info['event_left_score']=(string)($list_info['event_left_score']+$row_sub[0]['defen']);
		$list_info['event_right_score']=(string)($list_info['event_right_score']+$row_sub[1]['defen']);
		

		
		//开球时间
		$row['start_time']=DB::result_first("select start_time from tbl_baofen where 1=1 and fenzu_id='".$row['fenzu_id']."' $big_where order by start_time asc ");
		if($row['start_time']<time())
		{
			//如果比赛已经开始或结束，则对比成绩
			if($row_sub[0]['up_total'] > $row_sub[1]['up_total'])
			{
				$now_up_total =$row_sub[0]['up_total'];
				$now_be_num =$row_sub[0]['be_num'];
				$row['status_ico']=$list_info['event_left_ico'];
			}
			else if($row_sub[0]['up_total'] < $row_sub[1]['up_total'])
			{
				$now_up_total =$row_sub[1]['up_total'];
				$now_be_num =$row_sub[1]['be_num'];
				$row['status_ico']=$list_info['event_right_ico'];
			}
			else if($row_sub[0]['up_total'] == $row_sub[1]['up_total'])
			{
				$now_up_total =0;
				$now_be_num =0;
				
				
			}
			else
			{
				
			}

			
			if($now_up_total==0)
			{
				$row['status_text']='as';
			}
			else
			{
				if($now_be_num>0)
				{
					$row['status_text'] .= $now_up_total." & ".$now_be_num;
				}
				else
				{
					$row['status_text'] .= "Wins,".$now_up_total."UP";
					
					if($row['win']=='left')
					{
						$row['status_ico']="".$list_info['event_left_ico'];
					}
					else if($row['win']=='right')
					{
						$row['status_ico']="".$list_info['event_right_ico'];
					}
					else
					{
					}
					
					
		
				}
			}
		
		}
		else
		{
			$row['status_text']=date("i:s",$row['start_time']);
			
		}
		

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









//function ||


	
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

function Getdefen($score1,$score2,$is_end)
{
	if($is_end==1&&$score1<200&&$score2<200)
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
 
function Getwin($score1,$score2,$is_end,$type)
{
	
	if($is_end==1&&$score1<200&&$score2<200)
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

