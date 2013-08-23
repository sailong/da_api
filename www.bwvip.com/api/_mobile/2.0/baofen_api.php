<?php
/*
* 赛事直播 v2.0 
*/

if(!defined('IN_DISCUZ'))
{
    exit('Access Denied');
}


$t=time();
$ac=$_G['gp_ac'];// show显示列表、记录 edit修改记录 del 删除 rank排名
$uid=$_G['gp_uid'];
$sid=$_G['gp_sid']; //赛事ID
$lun=$_G['gp_lun'];//赛事第几轮  
$limit = $_G['gp_limit'] ? $_G['gp_limit'] : '10';//显示条数



//显示赛事
if($ac=='rank')
{
	$limit=300;
	if($sid==30)
	{
		$limit=156;
	}

	$pic_width=$_G['gp_pic_width'];
	$login_uid=$_G['gp_login_uid'];

	$lun=1;
	if($sid>0)
	{
		$event_sql=" and event_id='".$sid."' ";
	}
	else
	{
		$event_sql =" and event_is_tj='Y' ";
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
	
	//event_info
	$event_info=DB::fetch_first("select event_id,event_name,event_uid,event_fenzhan_id,event_logo,event_logo_small,event_timepic,event_starttime,event_endtime,event_content,event_state,event_is_tj,event_is_baoming,event_addtime,event_is_viewscore,event_lun_num from tbl_event where 1=1 ".$event_sql." order by event_addtime desc limit 1 ");
	if(!empty($event_info))
	{
		
		if($login_uid)
		{
			$bm=DB::fetch_first("select bm_id,code_pic from ".DB::table("home_dazbm")." where uid='".$login_uid."' and pay_status=1 ");
			if($bm['bm_id'])
			{
				$event_info['event_baoming_state']=$bm['bm_id'];
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
		//print_r($event_info);

		//处理分站
		if($_G['gp_now_fenzhan_id'])
		{
			$now_fenzhan_id=$_G['gp_now_fenzhan_id'];
		}
		else
		{
			$now_fenzhan_id=$event_info['event_fenzhan_id'];
		}
		
		//echo $now_fenzhan_id;
		
		
		if($now_fenzhan_id)
		{
			$fenzhan_info=DB::fetch_first("select parent_id from tbl_fenzhan where fenzhan_id='".$now_fenzhan_id."' ");
			
			//下级分站
			if($fenzhan_info['parent_id'])
			{
				$parent_fenzhan_id=$fenzhan_info['parent_id'];
			}
			else
			{
				$parent_fenzhan_id=$now_fenzhan_id;
			}
			
			$sub_arr=array();
			$sub_arr[]=$parent_fenzhan_id;
			
			$sub_fenzhan=DB::query("select fenzhan_id from tbl_fenzhan where parent_id='".$parent_fenzhan_id."'  and event_id='".$sid."' ");
			while($sub_row=DB::fetch($sub_fenzhan))
			{
				$sub_arr[]=$sub_row['fenzhan_id'];
			}
			
		}
		else
		{
			$sub_arr=array();
			$sub_fenzhan=DB::query("select fenzhan_id from tbl_fenzhan where event_id='".$sid."' ");
			while($sub_row=DB::fetch($sub_fenzhan))
			{
				$sub_arr[]=$sub_row['fenzhan_id'];
			}
		}
		
		
		
		if(count($sub_arr)>=1)
		{
			$baofen_sql =" and ( ";
			for($i=0; $i<count($sub_arr); $i++)
			{
				if(count($sub_arr)-$i==1)
				{
					$baofen_sql .=" fenzhan_id='".$sub_arr[$i]."' ";
				}
				else
				{
					$baofen_sql .=" fenzhan_id='".$sub_arr[$i]."' or ";
				}
				
			}
			$baofen_sql .=" )";
			
		}
		else
		{
			$baofen_sql=" and (event_id='".$sid."') ";
		}
	
		//$baofen_sql=" and (event_id='".$sid."') ";
		
		//echo $baofen_sql;
		//echo "<hr>";
		
	
		
		//选择成绩
		$lun_num = DB::result_first("select max(lun) from tbl_baofen where 1=1 ".$baofen_sql." and event_id<>0 limit 1 ");
		if($lun_num)
		{
			$query = DB::query(" SELECT id,uid,lun,event_id,total_score,zong_score,score,par,tianshu,total_sum_ju,event_apply_id,username,event_user_id ,status,dateline,jiadong_status FROM (select event_id,baofen_id as id,uid,lun,total_score,zong_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu,total_sum_ju ,total_ju_par,total_ju_par1,total_ju_par2,total_ju_par3,event_apply_id,realname,realname as username,event_user_id,status,dateline,jiadong_status from tbl_baofen where 1=1 ".$baofen_sql." and source='ndong' order by status asc,total_sum_ju asc,jiadong_status desc) as t2 group by event_user_id order by status desc,total_sum_ju asc,jiadong_status desc limit 0,$limit");
			
			//echo " SELECT id,uid,lun,event_id,total_score,zong_score,score,par,tianshu,total_sum_ju,event_apply_id,username,event_user_id ,status,dateline,jiadong_status FROM (select event_id,baofen_id as id,uid,lun,total_score,zong_score,score,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu,total_sum_ju ,total_ju_par,total_ju_par1,total_ju_par2,total_ju_par3,event_apply_id,realname,realname as username,event_user_id,status,dateline,jiadong_status from tbl_baofen where 1=1 ".$baofen_sql." and source='ndong' order by status asc,total_sum_ju asc,jiadong_status desc) as t2 group by event_user_id order by status desc,total_sum_ju asc,jiadong_status desc limit 0,$limit";
			//echo "<hr>";
			
			$i=0;
			while($row = DB::fetch($query))
			{

				//jiadong_status
				if($row['uid'])
				{
					$row['jiadong_status'] = DB::result_first("select max(jiadong_status) from tbl_baofen where 1=1 ".$baofen_sql." and uid='".$row['uid']."' limit 1 ");
				}
				else
				{
					$row['jiadong_status'] = DB::result_first("select max(jiadong_status) from tbl_baofen where 1=1 ".$baofen_sql." and event_user_id='".$row ['event_user_id']."' limit 1 ");
				}
				
				$zongbiaogan=0;
				$j=0;
				$row['true_sort']=0;
				for($ii=0; $ii<$lun_num; $ii++)
				{
					$j=$ii+1;
					$lun_info=array();
					if($row['uid'])
					{
						$lun_info = DB::fetch_first("select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu,status from tbl_baofen where 1=1 ".$baofen_sql." and uid='".$row['uid']."' and lun='".$j."' and source='ndong' order by dateline asc  ");
						
						//echo "select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu,status from tbl_baofen where 1=1 ".$baofen_sql." and uid='".$row['uid']."' and lun='".$j."' and source='ndong' order by dateline asc  ";
						//echo "<hr>";
					}
					else
					{
						$lun_info = DB::fetch_first("select baofen_id,sid,uid,total_score,score,total_ju_par,par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu,status from tbl_baofen where 1=1 ".$baofen_sql." and event_user_id='".$row ['event_user_id']."' and lun='".$j."'  and source='ndong'  order by dateline asc  ");
					}
		
					$zongbiaogan=$zongbiaogan+(end(explode("|",$lun_info['par'])));
			
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
		
					$p_arr=$prr_new;
					$s_arr=$arr_new;
					
					if(!empty($s_arr))
					{
						$c_arr=array();
					}
					
					$c_arr=dong_color($s_arr,$p_arr);
					
					if($j==1)
					{
						$score_1=$lun_info['score'];
						$color_1=$c_arr;
						$lun_1=$lun_info['total_score'];
						$ju_1=$lun_info['total_ju_par'];
						
						$par_1=$lun_info['par'];
						
					}
					if($j==2)
					{
						$score_2=$lun_info['score'];
						$lun_2=$lun_info['total_score'];
						$ju_2=$lun_info['total_ju_par'];
			
					
						$color_2=$c_arr;
					}
					if($j==3)
					{
						$score_3=$lun_info['score'];
						$lun_3=$lun_info['total_score'];
						$ju_3=$lun_info['total_ju_par'];
			
					
						$color_3=$c_arr;
					}
					if($j==4)
					{
						$score_4=$lun_info['score'];
						$lun_4=$lun_info['total_score'];
						$ju_4=$lun_info['total_ju_par'];
						
						$color_4=$c_arr;
					}

				}

				$row['ju_par_total']=get_ju_par_total_sort($ju_1,$ju_2,$ju_3,$ju_4);
				if($row['zong_score']>0)
				{
					$row['ju_par_total']=ju_par_format(get_ju_par_total_view($ju_1,$ju_2,$ju_3,$ju_4));
				}
				else
				{
					$row['ju_par_total']="-";
				}
				
				
				if($lun_1>0)
				{
					$row['lun_1']=ju_par_format($ju_1);	
					$row['ju_par_1']=ju_par_format($ju_1);
				}
				else
				{
					$row['lun_1']="-";	
					$row['ju_par_1']="-";	
				}

				if($lun_2>0)
				{
					$row['lun_2']=ju_par_format($ju_2);	
					$row['ju_par_2']=ju_par_format($ju_2);
				}
				else
				{
					$row['lun_2']="-";	
					$row['ju_par_2']="-";	
				}

				if($lun_3>0)
				{
					$row['lun_3']=ju_par_format($ju_3);	
					$row['ju_par_3']=ju_par_format($ju_3);
				}
				else
				{
					$row['lun_3']="-";	
					$row['ju_par_3']="-";	
				}

				if($lun_4>0)
				{
					$row['lun_4']=ju_par_format($ju_4);
					$row['ju_par_4']=ju_par_format($ju_4);
				}
				else
				{
					$row['lun_4']="-";	
					$row['ju_par_4']="-";	
				}
				
				$row['color_1']=$color_1;
				$row['color_2']=$color_2;
				$row['color_3']=$color_3;
				$row['color_4']=$color_4;

				$row['score_1']=$score_1;
				$row['score_2']=$score_2;
				$row['score_3']=$score_3;
				$row['score_4']=$score_4;
				if(!$score_4)
				{
					$row['color_4']=null;
					$row['score_4']=null;
				}
				if(!$score_3)
				{
					$row['color_3']=null;
					$row['score_3']=null;
				}
				if(!$score_2)
				{
					$row['color_2']=null;
					$row['score_2']=null;
				}
				if(!$score_1)
				{
					$row['color_1']=null;
					$row['score_1']=null;
				}
		
				$row['zongbiaogan']=$zongbiaogan;

				$row['today_score']=ju_par_format($row['total_ju_par']);
				$row['total_score']=(string)$row['zong_score'];
				if($row['total_score']==1000)
				{
					$row['total_score']='-';
				}
				
				$row['score_status']=get_thru($sid,$row['fenzhan_id'],$row['uid'],$row['event_user_id']);

				$s_arr=explode("|",$row['score']);
				unset($s_arr[9]);
				unset($s_arr[19]);
				unset($s_arr[20]);
				$str_new=implode("|",$s_arr);
				$arr_new=explode("|",$str_new);

				$row['score_sub']=array_default_value($arr_new);
				$gscore[] = array_default_value($row,array('status','score_1','score_2','score_3','score_4','color_4','color_1','color_2','color_3','color_4')); 
			}
			$i++;
			
		}//if max_lun
	
		
		
		if(!empty($gscore))
		{

			for($i=0; $i<count($gscore); $i++)
			{
				
				if($gscore[$i]['status']==-4)
				{
					$gscore[$i]['order']="CUT";
				}
				else if(intval($gscore[$i]['zong_score'])>0)
				{
				
					$true_order=$i+1;
					$t_str="";
					if($i==0)
					{
						if($gscore[$i]['total_sum_ju']==$gscore[$i+1]['total_sum_ju'])
						{
							$gscore[$i]['true_order']=1;
							$view_order="T".$gscore[$i]['true_order'];
						}
						else
						{
							$gscore[$i]['true_order']=$true_order;
							$view_order=$true_order;
						}
					}
					else
					{
						//从第2个开始
						if($gscore[$i]['total_sum_ju']==$gscore[$i-1]['total_sum_ju'])
						{
							$gscore[$i]['true_order']=$gscore[$i-1]['true_order'];
							$view_order="T".$gscore[$i]['true_order'];
							$gscore[$i-1]['order']=$view_order;
						}
						else
						{
							$gscore[$i]['true_order']=$true_order;
							$view_order=$true_order;
						}
						
					}
					
					$gscore[$i]['order']=$view_order;
				
				
				}
				else
				{
					$gscore[$i]['order']="-";
				}
				
				$gscore[$i]['order']=(string)$gscore[$i]['order'];
			}
		}
		else
		{
			$gscore=null;
		}
		
		

		
		
		

		//微博列表
		$saishi_name=$event_info['event_name'];
		$list=DB::query("select tid,uid, (select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid)  as username,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid limit 1) as `longtext`,imageid,content,replys,forwards,dateline,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%".$saishi_name."%' order by dateline desc limit 5 ");
		while($row = DB::fetch($list) )
		{
			$imageids_arr = explode(',',$row['imageid']);
						
			$pic_ids = implode("','",$imageids_arr);
			unset($imageids_arr);
			$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
			unset($pic_ids);
			
			$pic_i=0;
			while($pic_row = DB::fetch($topic_img_rs) ){
				$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
				$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
				$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
				$pic_i++;
			}
			unset($topic_img_rs,$pic_i,$pic_row);
			if(!empty($pic_list)) {
				$row['pic_list'] = $pic_list;
			}else{
				$row['pic_list'] = null;
			}
			$photo_pic = reset($pic_list);
			if($photo_pic)
			{
				$row['photo_big']=$photo_pic['photo_big'];
				$row['photo_mibble']=$photo_pic['photo_mibble'];
				$row['photo_small']=$photo_pic['photo_small'];
			}
			else
			{
				$row['photo_big']=null;
				$row['photo_small']=null;
			}
			unset($pic_list,$photo_pic);
			$content_tmp = cutstr_html($row['content']);
			$row['content']=cutstr_html($row['longtext']);
			if(empty($row['content']))
			{
				$row['content']=$content_tmp;
			}
			//$row['content']=cutstr_html($row['content']);
			$row['dateline']=date("Y-m-d G:i",$row['dateline']);

			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
			if($row['voice'])
			{
				$row['voice']=$site_url."/weibo/".$row['voice']."";
			}
			$topic_list[]=array_default_value($row,array('pic_list'));
		}
		
		
		
		
		
		/*接口返回的参数*/
		$response         = 0;
		$error_state      = 0;
		$data['title']    = "scorerank";
		$data['data'] = array(
			'sid'=>$event_info['event_uid'], 
			'realname'=>$event_info['event_name'], 
			'event_id'=>$event_info['event_id'],
			'event_fenzhan_id'=>$event_info['event_fenzhan_id'],
			'event_is_viewscore'=>$event_info['event_is_viewscore'],
			'event_lun_num'=>$event_info['event_lun_num'],
			'lun'=>(string)$lun,
			'lun_num'=>(string)$lun_num,
			'event_pic'=>$event_info['event_timepic'],
			'event_logo'=>$event_info['event_logo'],
			'event_logo_small'=>$event_info['event_logo_small'],
			'event_pic_width'=>$event_info['event_timepic_width'],
			'event_pic_height'=>$event_info['event_timepic_height'],
			'event_content'=>str_replace('src="','src="'.$site_url.'/',$event_info['event_content']),
			'event_is_baoming'=>$event_info['event_is_baoming'],
			'event_baoming_state'=>$event_info['event_baoming_state'],
			'event_baoming_pic'=>$event_info['event_baoming_pic'],
			'score_list'=>$gscore,
			'topic_list'=>$topic_list,
		);

		//print_r($data);
		api_json_result(1,0,$api_error['card']['10020'],$data);
		
		
	
	}
	else
	{
		$data['title']   = "scorerank";
		$data['data'] = null;
		api_json_result(1,1,"该赛事不存在或已被删除",$data);
	}
	
	


	
}





//距标准杆格式
function ju_par_format($option)
{
	$option=intval($option);

	if($option == 0)
	{
		$dataInfo = "E";
	}
	else if ($option > 0)
	{
		if($option<900)
		{
			$dataInfo = "+" . $option;
		}
		else
		{
			$dataInfo = "-";
		}
		
		$dataInfo = "+" . $option;
	}
	else if ($option < 0)
	{
		$dataInfo = $option;
	}
	else
	{
		$dataInfo = "-";
	}


	return (string)$dataInfo;
}



?>