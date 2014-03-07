<?php
/*
*
* card_api.php
* by zhanglong 2013-05-21
* field app 赛事详细页
*
*/
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


/**
 * 获取成绩卡
 * 
 //
$uid=$_G['gp_uid']; 该用户的成绩卡列表
$id=$_G['gp_id'];单条成绩卡记录
 */


$t=time();

$ac=$_G['gp_ac'];// show显示列表、记录 edit修改记录 del 删除 rank排名
$uid=$_G['gp_uid'];
$id=$_G['gp_id'];//成绩卡单条记录ID
$sid=$_G['gp_sid']; //赛事ID
$lun=$_G['gp_lun'];//赛事第几轮  
$limit = $_G['gp_limit'] ? $_G['gp_limit'] : '10';//显示条数

$strwhere=$id?' and id='.$id:'';

$username = DB::result_first( "select realname  from " . DB::table ( 'common_member_profile' ) . "  where uid='$uid' ");

//添加记录
if($ac==='insert')
{ 
	$arr ['uid'] = $uid;
	$arr ['fuid'] = $fuid;
	$arr ['par'] = $par;	
	$arr ['score'] = $score;
	$arr ['pars']  =$pars;
	$arr ['total_score']  =$total_score;
	$arr ['addtime']  = time();
	$row = DB::insert('common_score', $arr);
	api_json_result(1,0,$api_error['card']['10020'],$data);
}

//删除记录 
if($ac==='del'){
	 
	 $sql="delete from " . DB::table ( 'common_score' ) . "  where id='$id'";
	 $re=DB::query($sql); 
	api_json_result(1,0,$api_error['card']['10020'],$data); 
}


//修改记录 
if($ac==='edit')
{
	
	$showtime = time();
	//$sql="update " . DB::table ( 'tmsg' ) . " set num=num+1,  num0=num0+1, dateline='$showtime'  where mobile='$mobile'";
	//$re=DB::query($sql); 

	api_json_result(1,0,$api_error['card']['10020'],$data);
	
}


//显示记录 
if($ac==='show')
{

	if($uid <= 0) {
		if($uid == -1) {
			 api_json_result(1,10011,$api_error['register']['10021'],$data);
		}  
	}
	else
	{ 
	
		$query = DB::query("select id,uid,fuid,par,score,pars,total_score,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_score').".uid) as event_name from ".DB::table('common_score')."  where uid=$uid $strwhere order by addtime desc");
		while($row = DB::fetch($query))
		{
			$row['iframe_url']=$site_url."/nd/score.php?ndid=".$row['uid']."&size=small";
			$gscore[] = array_default_value($row); 
		}
		 
	if($gscore){
	
	/*接口返回的参数*/
		$response         = 0;
		$error_state      = 0;
		$data['title']    = "scorecard";
		$data['data'] = array_default_value(array(
							'uid'=>$uid,
							'username'=>$username,	 
							'list_data'=>$gscore,
							 ));
		//print_r($data);
		api_json_result(1,0,$api_error['card']['10020'],$data);
	}
	}
}



//显示排名 新 赛事功能（赛事详细）
if($ac=='rank')
{
	$limit=154;

	$pic_width=$_G['gp_pic_width'];
	$login_uid=$_G['gp_login_uid'];

	$lun=$_G['gp_lun'];
	if($lun)
	{
		$lun_sql=" and lun='".$lun."' ";
	}
	
	$event_id=$_G['gp_event_id'];
	
	if($event_id>0)
	{
		$sql=" and event_id='".$event_id."' ";
	}
	else
	{
		$sql =" and event_is_tj='Y' ";
	}

	$source=$_G['gp_source'];
	if($source)
	{
		$source_sql =" and source='waika' ";
	}
	else
	{
		$source_sql =" and source='ndong' ";
	}

	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where 1=1 ".$sql." order by event_addtime desc limit 1 ");
	$event_info = array_default_value($event_info);
	if($login_uid)
	{
		$bm=DB::fetch_first("select event_apply_id,code_pic from tbl_event_apply where uid='".$login_uid."' and event_id='".$event_id."' and event_apply_state=1 order by event_apply_addtime desc");
		

		if($bm['event_apply_id'])
		{
			$event_info['event_baoming_state']=$bm['event_apply_id'];
			if($bm['code_pic'])
			{
				$event_info['event_baoming_pic']=$site_url."".$bm['code_pic'];
			}
			else
			{
				$event_info['event_baoming_pic']="";
			}

		}
		else
		{
			$event_info['event_baoming_state']="0";
			$event_info['event_baoming_pic']="";
		}
		
	}
			

	//print_r($event_info);
	if($event_info['event_logo'])
	{
		$event_info['event_logo']=$site_url."/".$event_info['event_logo'];
	}
	if($event_info['event_timepic'])
	{
		$event_info['event_timepic']=$site_url."/".$event_info['event_timepic'];
		list($width, $height, $type, $attr) = getimagesize($event_info['event_timepic']);
		$event_info['event_timepic_width']=$width;
		$event_info['event_timepic_height']=$height;
	}

	if($event_info['event_content'])
	{
		$event_info['event_content']=str_replace("http://192.168.1.151:806","",$event_info['event_content']);
	}
	if($pic_width)
	{
		$event_info['event_content']=str_replace("<img ","<img width=\"".$pic_width."\" ",$event_info['event_content']);
	}

	//微博列表
	$saishi_name=$event_info['event_name'];
	$list=DB::query("select tid,uid, (select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid)  as username,content,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$saishi_name."%' order by dateline desc limit 5 ");
	while($row = DB::fetch($list) )
	{
		if($row['photo'])
		{
			$row['photo_big']=$site_url."/weibo/".$row['photo'];
			$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
		}
		else
		{
			$row['photo_big']=null;
			$row['photo_small']=null;
		}
		unset($row['photo']);

		$row['content']=cutstr_html($row['content']);
		$row['dateline']=date("Y-m-d G:i",$row['dateline']);

		$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
		if($row['voice'])
		{
			$row['voice']=$site_url."/weibo/".$row['voice']."";
		}
	        $row = array_default_value($row);
		$row = check_field_to_relace($row,array('replys'=>'0','forwards'=>'0'));
		$topic_list[]=$row;
	}



	if(!empty($event_info))
	{


			$sid=$event_info['event_uid'];
			$now_fz_id=$event_info['event_fenzhan_id'];
			
			
				//最大轮数
				$lun_num = DB::result_first("select max(lun) from tbl_score where event_id='".$event_id."' and fenzhan_id='".$now_fz_id."' and uid >0 limit 1 ");
				
				$query = DB::query(" SELECT score_id,uid,event_id,lun,total_score,zong_score,score,par,tianshu FROM (select score_id,uid,event_id,lun,total_score,zong_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_score where event_id='".$event_id."' and uid >0 and fenzhan_id='".$now_fz_id."' order by lun desc,zong_score asc ,tianshu asc) as t2 group by uid order by lun desc,zong_score asc ,tianshu asc  limit 0,$limit");

				$i=0;
				while($row = DB::fetch($query))
				{
					$zongbiaogan=0;
					for($j=1; $j<=$lun_num; $j++)
					{
						$lun_info="";
						$lun_info = DB::fetch_first("select score_id,event_id,uid,total_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_score where event_id='".$event_id."' and fenzhan_id='".$now_fz_id."' and uid='".$row['uid']."' and lun='".$j."' order by dateline asc limit 1 ");
						if($lun_info['score'])
						{
							$s_arr=explode("|",$lun_info['score']);
							unset($s_arr[9]);
							unset($s_arr[19]);
							unset($s_arr[20]);
							$str_new=implode("|",$s_arr);
							$arr_new=explode("|",$str_new);
							$lun_info['score']=$arr_new;
						}
						
						if($lun_info['par'])
						{
							$p_arr=explode("|",$lun_info['par']);
							unset($p_arr[9]);
							unset($p_arr[19]);
							unset($p_arr[20]);
							$ptr_new=implode("|",$p_arr);
							$prr_new=explode("|",$ptr_new);
						}
						//$lun_info['par']=$prr_new;
						
						//print_r($lun_info);
						
						$p_arr=$prr_new;
						$s_arr=$arr_new;
						
						if(!empty($s_arr) )
						{
							$c_arr=array();
						}
						
						for($i=0; $i<count($p_arr); $i++)
						{
							if($s_arr[$i]!="" )
							{
								if($s_arr[$i]-$p_arr[$i]==3)
								{
									$c_arr[$i]=1;
								}
								else if($s_arr[$i]-$p_arr[$i]==2)
								{
									$c_arr[$i]=2;
								}
								else if($s_arr[$i]-$p_arr[$i]==1)
								{
									$c_arr[$i]=3;
								}
								else if($s_arr[$i]-$p_arr[$i]==0)
								{
									$c_arr[$i]=4;
								}
								else if($s_arr[$i]-$p_arr[$i]==-1)
								{
									$c_arr[$i]=5;
								}
								else if($s_arr[$i]-$p_arr[$i]==-2)
								{
									$c_arr[$i]=6;
								}
								else if($s_arr[$i]-$p_arr[$i]==-3)
								{
									$c_arr[$i]=7;
								}
								else
								{
									$c_arr[$i]=0;
									//$c_arr[$i]=$s_arr[$i]-$p_arr[$i];
								}
							}
							/*
							unset($c_arr[9]);
							unset($c_arr[19]);
							unset($c_arr[20]);
							$ctr_new=implode("|",$c_arr);
							$c_arr=explode("|",$ctr_new);
							*/
							//print_r($c_arr);
							//echo "<hr>";
						}
					
						if($j==1)
						{
							$score_1=$lun_info['score'];
							$color_1=$c_arr;
							$lun_1=$lun_info['total_score'];
							$ju_1=(end(explode("|",$row['score']))-end(explode("|",$row['par'])));
							
							$par_1=$lun_info['par'];
							
						}
						if($j==2)
						{
							$score_2=$lun_info['score'];
							$lun_2=$lun_info['total_score'];
							$ju_2=(end(explode("|",$row['score']))-end(explode("|",$row['par'])));
							$color_2=$c_arr;
						}
						if($j==3)
						{
							$score_3=$lun_info['score'];
							$lun_3=$lun_info['total_score'];
							$ju_3=(end(explode("|",$row['score']))-end(explode("|",$row['par'])));
							$color_3=$c_arr;
						}
						if($j==4)
						{
							$score_4=$lun_info['score'];
							$lun_4=$lun_info['total_score'];
							$ju_4=(end(explode("|",$row['score']))-end(explode("|",$row['par'])));
							$color_4=$c_arr;
						}

					}
					
					
					$row['ju_par_total']=($ju_1)+($ju_2)+($ju_3)+($ju_4);
					if(!$row['zong_score'])
					{
						$row['zong_score']=($lun_1)+($lun_2)+($lun_3)+($lun_4);
						$res=DB::query("update tbl_score set zong_score='".$row['zong_score']."' where uid='".$row ['uid']."' and event_id='".$row ['event_id']."'  ");
						//echo "update tbl_score set zong_score='".$row['zong_score']."' where uid='".$row ['uid']."' and event_id='".$row ['event_id']."'  ";
					}

					$row['lun_1']=$lun_1;
					$row['lun_2']=$lun_2;
					$row['lun_3']=$lun_3;
					$row['lun_4']=$lun_4;
					$row['ju_par_1']=$ju_1;
					$row['ju_par_2']=$ju_2;
					$row['ju_par_3']=$ju_3;
					$row['ju_par_4']=$ju_4;
					
					$row['color_1']=$color_1;
					$row['color_2']=$color_2;
					$row['color_3']=$color_3;
					$row['color_4']=$color_4;

					
					$row['score_1']=$score_1;
					$row['score_2']=$score_2;
					$row['score_3']=$score_3;
					$row['score_4']=$score_4;
					if(!$lun_1)
					{
						$row['lun_1']='';
					}
					if(!$lun_2)
					{
						$row['lun_2']='';
					}
					if(!$lun_3)
					{
						$row['lun_3']='';
					}
					if(!$lun_4)
					{
						$row['lun_4']='';
					}
					if(empty($score_1))
					{
						$row['score_1']=null;
					}
					if(empty($score_2))
					{
						$row['score_2']=null;
					}
					if(empty($score_3))
					{
						$row['score_3']=null;
					}
					if(empty($score_4))
					{
						$row['score_4']=null;
					}

					$row['today_score']=(string)(end(explode("|",$row['score']))-end(explode("|",$row['par'])));
					$row['total_score']=(string)$row['zong_score'];
					//$row['score_status']="F/".$row['lun'];
					$row['score_status']="F";

					$s_arr=explode("|",$row['score']);
					unset($s_arr[9]);
					unset($s_arr[19]);
					unset($s_arr[20]);
					$str_new=implode("|",$s_arr);
					$arr_new=explode("|",$str_new);
					
					$row['score_sub']=array_default_value($arr_new);
					
					
					
					$gscore[] = array_default_value($row,array('score_1','score_2','score_3','score_4','color_4','color_1','color_2','color_3')); 
				}
				$i++;
			
				

			

			//print_r($gscore);
			if($gscore)
			{
				$i=1;
				foreach ($gscore as $key => $value )
				{ 
					$gscore [$key] ['username'] =  gettruename($gscore [$key] ['uid']);
					if(!$gscore [$key] ['username'])
					{
						$gscore [$key] ['username']="";
					}
					$gscore [$key] ['order'] = (string)$i++;
					/*
					if($gscore [$key] ['lun']!=$lun_num)
					{
						$gscore [$key] ['order'] = "CUT";  	
					}
					else
					{
						$gscore [$key] ['order'] = "".$i++."";  
					}
					*/
					
				}
			}
					
			//重新排序
			//$gscore=array_sort_by_field($gscore, 'zong_score',true);
			
			if(empty($gscore)) {
			    $gscore = null;
			}
	        if(empty($topic_list)) {
			    $topic_list = null;
			}
			

			
			/*接口返回的参数*/
			$response         = 0;
			$error_state      = 0;
			$data['title']    = "scorerank";
			$data['data'] = array_default_value(array(
				'event_id'=>$event_info['event_id'],
				'realname'=>$event_info['event_name'], 
				'event_fenzhan_id'=>$event_info['event_fenzhan_id'],
				'lun'=>$lun,
				'lun_num'=>$lun_num,
				'event_pic'=>$event_info['event_timepic'],
				'event_logo'=>$event_info['event_logo'],
				'event_pic_width'=>$event_info['event_timepic_width'],
				'event_pic_height'=>$event_info['event_timepic_height'],
				'event_content'=>str_replace('src="','src="'.$site_url.'/',$event_info['event_content']),
				'event_is_baoming'=>$event_info['event_is_baoming'],
				'event_baoming_state'=>$event_info['event_baoming_state'],
				'event_baoming_pic'=>$event_info['event_baoming_pic'],
				'score_list'=>array_default_value($gscore),
				'topic_list'=>array_default_value($topic_list),
			 ));
			 

			//print_r($data);
			api_json_result(1,0,$api_error['card']['10020'],$data);


		}//if no event_info
		else
		{
			$data['title']   = "scorerank";
			$data['data'] = null;
			api_json_result(1,1,"该赛事不存在或已被删除",$data);
		}

	
}


//分站详细页
if($ac=="fenzhan_detail")
{
	$fenzhan_id=$_G['gp_fenzhan_id'];
	$source=$_G['gp_source'];

	$fenzhan=DB::fetch_first("select * from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' ");
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_logo,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime from tbl_event where event_id='".$fenzhan['event_id']."' order by event_addtime desc limit 1 ");
	if($event_info['event_logo'])
	{
		$event_info['event_logo']=$site_url."/".$event_info['event_logo'];
	}
	if($event_info['event_timepic'])
	{
		if($fenzhan['timepic'])
		{
			$event_info['event_timepic']=$fenzhan['timepic'];
		}
		$event_info['event_timepic']=$site_url."/".$event_info['event_timepic'];
		list($width, $height, $type, $attr) = getimagesize($event_info['event_timepic']);
		$event_info['event_timepic_width']=$width;
		$event_info['event_timepic_height']=$height;
	}

	if($event_info['event_content'])
	{
		$event_info['event_content']=str_replace("http://192.168.1.151:806","",$event_info['event_content']);
	}
	if($pic_width)
	{
		$event_info['event_content']=str_replace("<img ","<img width=\"".$pic_width."\" ",$event_info['event_content']);
	}

		
		$lun_num=1;
		////分站N洞成绩列表
		$list=DB::query("select baofen_id,score_id,uid,realname as username,total_ju_par, total_score,cave_1,cave_2,cave_3,cave_4,cave_5,cave_6,cave_7,cave_8,cave_9,cave_10,cave_11,cave_12,cave_13,cave_14,cave_15,cave_16,cave_17,cave_18,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18) as lin,is_end from tbl_baofen where fenzhan_id='".$fenzhan_id."' order by is_end desc,total_ju_par,lin ");
		while($row=DB::fetch($list))
		{
		
			$row['tianshu']="-1";
			$row['lun_num']="1";

			$row['today_score']=$row['total_ju_par'];
			if($row['today_score']==1000)
			{
				$row['today_score']='-';
			}
			
			$row['total_score']=Getstat($row['total_score']);
			$row['par']=str_replace(",","|","4,4,3,5,4,4,3,5,4,36,4,4,5,4,4,4,5,3,4,36,72");
			$row['score_status']="F";
			$out=$row['cave_1']+$row['cave_2']+$row['cave_3']+$row['cave_4']+$row['cave_5']+$row['cave_6']+$row['cave_7']+$row['cave_8']+$row['cave_9'];
			$in=$row['cave_10']+$row['cave_11']+$row['cave_12']+$row['cave_13']+$row['cave_14']+$row['cave_15']+$row['cave_16']+$row['cave_17']+$row['cave_18'];
			$total=$out+$in;

			$row['score']=$row['cave_1']."|".$row['cave_2']."|".$row['cave_3']."|".$row['cave_4']."|".$row['cave_5']."|".$row['cave_6']."|".$row['cave_7']."|".$row['cave_8']."|".$row['cave_9']."|".$row['cave_10']."|".$row['cave_11']."|".$row['cave_12']."|".$row['cave_13']."|".$row['cave_14']."|".$row['cave_15']."|".$row['cave_16']."|".$row['cave_17']."|".$row['cave_18'];

			$row['score_sub']=explode("|",$row['score']);
			$row['par_sub']=explode("|",$row['par']);

			unset($row['cave_1']);
			unset($row['cave_2']);
			unset($row['cave_3']);
			unset($row['cave_4']);
			unset($row['cave_5']);
			unset($row['cave_6']);
			unset($row['cave_7']);
			unset($row['cave_8']);
			unset($row['cave_9']);
			unset($row['cave_10']);
			unset($row['cave_11']);
			unset($row['cave_12']);
			unset($row['cave_13']);
			unset($row['cave_14']);
			unset($row['cave_15']);
			unset($row['cave_16']);
			unset($row['cave_17']);
			unset($row['cave_18']);
			$gscore[] = array_default_value($row);
		}
		

		//print_r($gscore);
		if($gscore)
		{
			$i=1;
			foreach ($gscore as $key => $value )
			{ 
				//$gscore [$key] ['username'] =  gettruename($gscore [$key] ['uid']);
				//$gscore [$key] ['order'] = '"'.$i++.'"';
				$gscore [$key] ['order'] = "".$i++."";
			}
		}
		
		
		//微博列表
		$saishi_name=gettruename($event_info['event_name']);
		$list=DB::query("select tid,uid, (select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid)  as username,content,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$saishi_name."%' order by dateline desc limit 5 ");
		while($row = DB::fetch($list) )
		{
			if($row['photo'])
			{
				$row['photo_big']=$site_url."/weibo/".$row['photo'];
				$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
			}
			else
			{
				$row['photo_big']=null;
				$row['photo_small']=null;
			}
			unset($row['photo']);

			$row['content']=cutstr_html($row['content']);
			$row['dateline']=date("Y-m-d G:i",$row['dateline']);

			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
			if($row['voice'])
			{
				$row['voice']=$site_url."/weibo/".$row['voice']."";
			}
			$row=array_default_value($row);
            $row = check_field_to_relace($row,array('replys'=>'0','forwards'=>'0'));
			$topic_list[]=$row;
		}
	
        if(empty($gscore)) {
		    $gscore = null;
		}
		/*接口返回的参数*/
		$response         = 0;
		$error_state      = 0;
		$data['title']    = "scorerank";
		$data['data'] = array_default_value(array(
			'event_id'=>$event_info['event_id'], 
			'realname'=>$event_info['event_name'], 
			'lun'=>"1", 
			'lun_num'=>$lun_num, 
			'event_pic'=>$event_info['event_timepic'],
			'event_logo'=>$event_info['event_logo'],
			'event_pic_width'=>$event_info['event_timepic_width'],
			'event_pic_height'=>$event_info['event_timepic_height'],
			'event_content'=>str_replace('src="','src="'.$site_url.'/',$event_info['event_content']),
			'event_is_baoming'=>$event_info['event_is_baoming'],
			'score_list'=>$gscore,
			'event_baoming_state'=>null,
			'event_baoming_pic'=>null,
			'topic_list'=>null,
		));

		//print_r($data);
		api_json_result(1,0,$api_error['card']['10020'],$data);
}



//分站列表
if($ac=="fenzhan_list")
{
	$event_id=$_G['gp_event_id'];
	$year=$_G['gp_year'];
	$list=DB::query("select fenzhan_id,event_id,fenzhan_name,starttime,endtime,field_id,(select fieldname from ".DB::table("common_field")." where uid=tbl_fenzhan.field_id ) as field_name from tbl_fenzhan where event_id='".$event_id."' and year='".$year."' ");
	$i=0;
	while($row=DB::fetch($list))
	{
		$i=$i+1;
		if($row['field_name']==null)
		{
			if($row['field_id']=="3802780")
			{
				$row['field_name']="大溪谷高尔夫俱乐部";
			}
			else
			{
				$row['field_name']="";
			}
			
		}

		$row['pic']=$site_url."/images/lx/q".$i.".png";
		$row['starttime']=date("Y-m-d",$row['starttime']);
		$row['endtime']=date("Y-m-d",$row['endtime']);
		$list_data[]=array_default_value($row);
	}
	
    if(empty($list_data)) {
	    $list_data = null;
	}
	$data['title']="list_data";
	$data['data']=$list_data;
	if(!empty($list_data))
	{
		api_json_result(1,0,$api_error['card']['10020'],$data);
	}
	else
	{
		api_json_result(1,1,"还没有分站",$data);
	}
}

//显示DQ RTD
function Getstat($tlcave)
{
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
			$dataInfo = "";
			break;
	}
	
	return $dataInfo;
}
function gettruename($uuid)
{
	$username = DB::result_first( "select realname  from " . DB::table ( 'common_member_profile' ) . "  where uid='$uuid' ");
	return $username ;
}

/**
 * 检查一维数组并替换数组中的某一项元素
 * @param array $arr      将要替换的数组
 * @param array $fields   要替换的字段
 * $fields = array(
 * 				'key'=>'val'   //key:将要替换的字段；val:最终替换后的结果
 * 			);
 */
function check_field_to_relace($arr=array(), $fields=array()) {
    if(empty($arr) || empty($fields)) {
        return null;
    }
    
    
    foreach($arr as $key=>$val) {
        if(is_array($arr[$key])) {
           //$arr[$key] = current(array_map(__FUNCTION__,array($arr[$key]),array($fields)));//处理多维度
           //$arr[$key] = check_field_to_relace($arr[$key],$fields);//处理多维度
           continue;
        }
        if(isset($fields[$key])) {
            $arr[$key]=$fields[$key];
        }
    }

    
    return $arr;
}
?>