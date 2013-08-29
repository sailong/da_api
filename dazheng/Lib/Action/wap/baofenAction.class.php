<?php
/**
 *    #Case		bwvip
 *    #Page		BaofenAction.class.php (报分)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-30
 */
class baofenAction extends wap_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}
	
	public function login()
	{	
		$lun=get('lun');
		if(!$lun)
		{
			$lun=1;
		}
		$this->assign('lun',$lun);
		$this->display();
	}
	
	public function baofen_login_action()
	{
		$username=post("username");
		$password=post("password");
		$lun=post("lun");
		
		$fenzhan_id=post("fenzhan_id");
		
		if($fenzhan_id)
		{
			if($username && $password)
			{
				$user=M("baofen_user")->where("username='".$username."' and password='".$password."' and fenzhan_id='".$fenzhan_id."'")->find();
				if($user['baofen_user_id'])
				{
					$_SESSION['baofen_user_id']=$user['baofen_user_id'];
					$_SESSION['baofen_username']=$user['username'];
					$_SESSION['fenzhan_id']=$user['fenzhan_id'];
					$_SESSION['lun']=$lun;

					$this->success("登录成功，现在可以报分了",U('wap/baofen/baofen',array('fenzhan_id'=>$fenzhan_id,'fenzu_id'=>$lun)));
				}
				else
				{
					$this->error("用户名或密码有误");
				}
			}
			else
			{
				$this->error("用户名和密码必须填写");
			}
		}
		else
		{
			$this->error("分站不存在，请确认报分地址是否正确");
		}
		
	}
	
	
	
	public function logout()
	{
		if(isset($_SESSION['baofen_user_id']))
		{
			unset($_SESSION['baofen_user_id']);
			unset($_SESSION['baofen_username']);
			unset($_SESSION['fenzhan_id']);			

			$this->success("退出成功",U('wap/baofen/login',array('fenzhan_id'=>get('fenzhan_id'),'lun'=>get('lun'))));
		}
		else
		{
			$this->error("您已经退出，现在跳转首页",U('wap/baofen/login',array('fenzhan_id'=>get('fenzhan_id'),'lun'=>get('lun'))));
		}
	}
	
	
	
	
	
	
	public function baofen()
	{
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id==50)
		{$str=" and fenzhan_id in(51,54,57,60)";}
		else
		{$str=" and fenzhan_id $fenzhan_id";}
		$fenzu_id=get("fenzu_id");
		if(!$fenzhan_id)
		{
			$fenzhan_id=$_SESSION['fenzhan_id'];
		}
		$lun=get("lun");
		if(!$lun)
		{
			$lun=$_SESSION['lun'];
			if(!$lun)
			{
				$lun=1;
			}
		}
		
		
		if(!$_SESSION['baofen_user_id'])
		{	
			echo "<script>location='".U('wap/baofen/login',array('fenzhan_id'=>$fenzhan_id,'lun'=>$lun))."';</script>";
			exit;
			//$this->error("请登录",U('field/public/login'));
		}
		
		
		$this->assign('fenzhan_id',$fenzhan_id);
		$this->assign('fenzu_id',$fenzu_id);
		$this->assign('lun',$lun); 
		if($fenzhan_id)
		{
			//$fenzu_list=D("fenzu")->fenzu_select_pro(" and fenzhan_id='".$fenzhan_id."'",999," fenzu_number asc ");
			$fenzu_list=M()->query("select * from tbl_baofen where  1 $str group by fenzu_id order by fenzu_id   ");  
			$this->assign('fenzu_list',$fenzu_list);
			
			$dong=M()->query("select dongs from tbl_baofen_user where baofen_user_id='".$_SESSION['baofen_user_id']."' ");
			$dongs=explode(",",$dong[0]['dongs']);
			//print_r($dongs);
			$this->assign("dongs",$dongs);
			
			$chengji_arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );
			$this->assign('chengji_arr',$chengji_arr);
			
			if(get("fenzu_id"))
			{
				$fenzu_id=get("fenzu_id");
				$strwhere="and fenzu_id='".$fenzu_id."' ";
			}
			else
			{
				$fenzu_id=$fenzu_list['item'][0]['fenzu_id']; 
			}
			 
			$fenzu_user=M()->query("select * from tbl_baofen where 1 $str $strwhere ");  
			
			
			$this->assign("fenzu_user",$fenzu_user);
			
			$this->display();
		}
		else
		{
			echo "分站不存在";
		}
	}
	
	
	//电脑版，不分组
	public function baofen_big()
	{
		$fenzhan_id=get("fenzhan_id");
		$fenzu_id=get("fenzu_id");
		if(!$fenzhan_id)
		{
			$fenzhan_id=$_SESSION['fenzhan_id'];
		}
		$lun=get("lun");
		if(!$lun)
		{
			$lun=$_SESSION['lun'];
			if(!$lun)
			{
				$lun=1;
			}
		}
		
		
		if(!$_SESSION['baofen_user_id'])
		{	
			echo "<script>location='".U('wap/baofen/login',array('fenzhan_id'=>$fenzhan_id,'lun'=>$lun))."';</script>";
			exit;
			//$this->error("请登录",U('field/public/login'));
		}
		
		
		$this->assign('fenzhan_id',$fenzhan_id);
		$this->assign('fenzu_id',$fenzu_id);
		$this->assign('lun',$lun); 
		if($fenzhan_id)
		{
			//$fenzu_list=D("fenzu")->fenzu_select_pro(" and fenzhan_id='".$fenzhan_id."'",999," fenzu_number asc ");
			$fenzu_list=M()->query("select * from tbl_baofen where fenzhan_id='".$fenzhan_id."' group by fenzu_id order by fenzu_id   ");  
			$this->assign('fenzu_list',$fenzu_list);
			
			$dong=M()->query("select dongs from tbl_baofen_user where baofen_user_id='".$_SESSION['baofen_user_id']."' ");
			$dongs=explode(",",$dong[0]['dongs']);
			//print_r($dongs);
			$this->assign("dongs",$dongs);
			
			$chengji_arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 );
			$this->assign('chengji_arr',$chengji_arr);
			
			if(get("fenzu_id"))
			{
				$fenzu_id=get("fenzu_id");
				$strwhere="and fenzu_id='".$fenzu_id."' ";
			}
			else
			{
				$fenzu_id=$fenzu_list['item'][0]['fenzu_id']; 
			}
			 
			$fenzu_user=M()->query("select * from tbl_baofen where fenzhan_id='".$fenzhan_id."' $strwhere ");  
			
			$this->assign("fenzu_user",$fenzu_user);
			
			$this->display();
		}
		else
		{
			echo "分站不存在";
		}
	}
	
	
	public function baofen_big_save_action()
	{ 
		$arr_b =  $_POST['userdk'];

		$fenzhan_id = post('fenzhan_id');
		$lun = post('lun');

		
		if($arr_b)
		{ 
			$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$fenzhan_id."' ")->find();
			$event_id=$fenzhan_info['event_id'];
			$field_id=$fenzhan_info['field_id'];
			$fenzhan_a=$fenzhan_info['fenzhan_a'];
			$fenzhan_b=$fenzhan_info['fenzhan_b'];
			
			//下级分站
			if($fenzhan_info['parent_id'])
			{
				$parent_fenzhan_id=$fenzhan_info['parent_id'];
			}
			else
			{
				$parent_fenzhan_id=$fenzhan_id;
			}
			$sub_arr=array();
			$sub_arr[]=$parent_fenzhan_id;
			$sub_fenzhan=M()->query("select fenzhan_id from tbl_fenzhan where parent_id='".$parent_fenzhan_id."' and event_id='".$event_id."' ");
			for($i=0; $i<count($sub_fenzhan); $i++)
			{
				$sub_arr[]=$sub_fenzhan[$i]['fenzhan_id'];
			}
			
			
			if(count($sub_arr)>=1)
			{
				$sub_fenzhan_sql =" and ( ";
				for($i=0; $i<count($sub_arr); $i++)
				{
					if(count($sub_arr)-$i==1)
					{
						$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$i]."' ";
					}
					else
					{
						$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$i]."' or ";
					}
					
				}
				$sub_fenzhan_sql .=" )";
			}
			else
			{
				$sub_fenzhan_sql=" and (event_id='".$event_id."') ";
			}
			
			
			
			$avs=0;
			foreach($arr_b as $key=>$value)
			{

				if($event_id && $field_id)
				{
					//$qc_par_result=M()->query("select fenzhan_a,fenzhan_b from tbl_fenzhan where field_id='".$field_id."' "); 
					$par = explode(',',$fenzhan_a.','.$fenzhan_b);
					$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
					$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
					$PTL = $POUT + $PIN; 
					$ttt=0;
					$total_score=0;
					foreach($value as $k=>$var)
					{
						// echo "$k=>$var<br>";
						if($k==30)
						{
							$sql_sets['thru'] = "`thru`='".$var."'";
						}
						else if($k==31)
						{	
							$sql_sets['status'] = "`status`='".$var."'";
						}
						else if($k==32)
						{	
							$sql_sets['jiadong_status'] = "`jiadong_status`='".$var."'";
						}
						else
						{
	
							$sql_sets['cave_' . $k] = "`cave_".$k."`='".$var."'";
							
						}

							$data['cave_' . $k] = $var;

						
						if($var>=0 && $k<30)
						{
							$total_score=$total_score+$var;
						}
				 
						//跳过
						if($var ==-3)
						{ 
							$ttt=999;
						}
						//DQ
						else if($var==-1)
						{
							$ttt=1000;
					
						}
						//取消
						else if($var ==-2)
						{
							$ttt=1001;
						}
						else
						{
							
						}
					}
					
					
					//插入更新操作 18洞成绩
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."'");
					$data ['baofen_id'] = $key;
					//$data ['lun'] = $lun; 
					$data ['event_id'] = $event_id;
					$data ['fenzhan_id'] = $fenzhan_id;
					$data ['field_id'] = $field_id; 
					//$data ['total_score'] = $total_score;  
					if($baofen[0]['baofen_id'])
					{
						$sql = "update tbl_baofen set ".(implode ( " , ",$sql_sets ))." where baofen_id='".$key."' ";
					} 
					
					/*
					echo "<hr>";	
					echo $sql;
					echo "<hr>";
					*/
					$rs = M()->query($sql);

					//统计总分
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."'");
					$avcave=0;
					for($i = 1; $i <= 18; $i ++)
					{
						if($baofen[0]['cave_'.$i]>0)
						{
							$avcave+=Gpar($baofen[0]['cave_'.$i],$par[$i-1]);
						}
					}
					$sql="update tbl_baofen set total_ju_par=$avcave where baofen_id='".$key."'"; 
					//echo $sql;
					//echo "<hr>";
					$res=M()->query($sql);
			
					$arrypar = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
					$i=0; 
						
					$row=M()->query("select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin from tbl_baofen where baofen_id='".$key."' ");
					for($i = 1; $i <= 21; $i ++) 
					{
						if ($i == '10')
						{
							$sdata [$i] = $row[0]['lout'];
						} 
						elseif ($i == '20')
						{
							$sdata [$i] = $row[0]['lin'];
						}
						elseif ($i == '21')
						{
							$sdata [$i] = $total_score;
						}
						elseif ($i > 9)
						{
							$sdata [$i] = $row[0]['cave_' . ($i - 1)];
						}
						else
						{
							$sdata [$i] = $row[0]['cave_' . $i];
						}
						
						if($sdata [$i]==0)
						{
							$sdata [$i]='-';
						}
					
					} 
					//初始化
					$total_eagle = 0;
					//birdie  
					$total_birdie = 0;
					//E  
					$total_evenpar = 0;
					//bogi  
					$total_bogi = 0;
					//doubles 
					$total_doubles = 0;
					
					for($i = 1; $i <= 21; $i ++) 
					{
						
						if($sdata [$i]!=0 )
						{										
							$data1 [$i] = Getpar ( $sdata [$i] - $par [$i - 1] );
							$dtl [$i] =  $sdata [$i] - $par [$i - 1] ;
							if($i != '10' && $i != '20' && $i != '21')
							{
								//eagle
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '-2' ? $total_eagle ++ : '';
								//birdie 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '-1' ? $total_birdie ++ : '';
								//E 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == 'E' ? $total_evenpar ++ : '';
								//bogi 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '+1' ? $total_bogi ++ : '';
								//doubles
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '+2' ? $total_doubles ++ : '';
							}
						}
						else
						{
							$data1[$i] = '-';
							$dtl[$i]='-';
						}
						
					}   
					$data1 [10] = $dtl [1] + $dtl [2] + $dtl [3] + $dtl [4] + $dtl [5] + $dtl [6] + $dtl [7] + $dtl [8]+ $dtl [9];
					$data1 [20] = $dtl [11] + $dtl [12] + $dtl [13] + $dtl [14] + $dtl [15] + $dtl [16] + $dtl [17]+ $dtl [18]+ $dtl [19];
					$data1 [21] = $data1 [10] +$data1 [20] ;
					
					$str = implode ( '|', $sdata );
					$str1 = implode ( '|', $data1 ); 
					$total_avepushs = floor ( $total_score / 18 ); 
					
					
			
					$arry ['par'] = "`par`='".$arrypar."'";	
					$arry ['pars'] = "`pars`='".$str1."'";	
					$arry ['score'] = "`score`='".$str."'";	 
					$arry ['total_score'] = "`total_score`='".$sdata[21]."'";	
					
					$arry ['total_avepushs'] = "`total_avepushs`='".$total_avepushs."'";	
					$arry ['total_eagle'] = "`total_eagle`='".$total_eagle."'";	
					$arry ['total_birdie'] = "`total_birdie`='".$total_birdie."'";	
					$arry ['total_evenpar'] = "`total_evenpar`='".$total_evenpar."'";	
					$arry ['total_bogi'] = "`total_bogi`='".$total_bogi."'";	
					$arry ['total_doubles'] = "`total_doubles`='".$total_doubles."'";	 
					$arry ['total_ju_par'] = "`total_ju_par`='".$avcave."'";	 
					$arry ['total_sum_ju'] = "`total_sum_ju`='".$total_sum_ju."'";	 
					$arry ['zong_score'] = "`zong_score`='".$zong_score."'";
				
					 
					$res=M()->query("update tbl_baofen set ".(implode(",",$arry))." where baofen_id='".$key."' ");
					//echo "update tbl_baofen set ".(implode(",",$arry))." where baofen_id='".$key."' ";
					//echo "<hr>";
					
				
						 
					//更新状态
					if($ttt)
					{
						$sql_sets ['total_score'] = "`total_score`='".$ttt."'";	
						$sql_sets ['is_end'] = "`is_end`='0'";	
						$sql_sets ['total_ju_par'] = "`total_ju_par`='1000'";	
						$res=M()->query("update tbl_baofen set ".(implode(",",$sql_sets))." where baofen_id='".$key."'");
						//echo "update tbl_baofen set ".(implode(",",$sql_sets))." where uid='".$key."' ";
						//echo "<hr>";
					} 
					
					
					
					//多轮成绩更新
					$lun_num = M()->query("select max(lun) as lun_num from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and event_id<>0 and source='ndong' limit 1 ");
					for($i=0; $i<$lun_num[0]['lun_num']; $i++)
					{
						$lun=$i+1;
						if($baofen[0]['uid'])
						{
							$lun_info = M()->query("select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and uid='".$baofen[0]['uid']."' and lun='".$lun."' and source='ndong' order by dateline asc ");
						}
						else
						{
							$lun_info = M()->query("select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and event_user_id='".$baofen[0]['event_user_id']."' and lun='".$lun."' and source='ndong' order by dateline asc ");
						}
						
						if($lun==1)
						{
							$ju_1=$lun_info[0]['total_ju_par'];
							$lun_1=$lun_info[0]['total_score'];
							if($ju_1>900)
							{
								$ju_1=0;
							}
							if($lun_1>900)
							{
								$lun_1=0;
							}
							
							$total_ju_par1=$lun_info[0]['total_ju_par'];
						}
						if($lun==2)
						{
							$ju_2=$lun_info[0]['total_ju_par'];
							$lun_2=$lun_info[0]['total_score'];
							if($ju_2>900)
							{
								$ju_2=0;
							}
							if($lun_2>900)
							{
								$lun_2=0;
							}
							
							$total_ju_par2=$lun_info[0]['total_ju_par'];
							
						}
						
						if($lun==3)
						{
							$ju_3=$lun_info[0]['total_ju_par'];
							$lun_3=$lun_info[0]['total_score'];
							if($ju_3>900)
							{
								$ju_3=0;
							}
							if($lun_3>900)
							{
								$lun_3=0;
							}
							$total_ju_par3=$lun_info[0]['total_ju_par'];
						}
						
						if($lun==4)
						{
							$ju_4=$lun_info[0]['total_ju_par'];
							$lun_4=$lun_info[0]['total_score'];
							if($ju_4>900)
							{
								$ju_4=0;
							}
							if($lun_4>900)
							{
								$lun_4=0;
							}
						}
					
					
					}
					
					$up_sql="";
					if($total_ju_par1)
					{
						$up_sql .=" ,total_ju_par1='".$total_ju_par1."' ";
					}
					
					if($total_ju_par2)
					{
						$up_sql .=" ,total_ju_par2='".$total_ju_par2."' ";
					}
					
					if($total_ju_par3)
					{
						$up_sql .=" ,total_ju_par3='".$total_ju_par3."' ";
					}
					
					
		
					//$total_sum_ju=get_ju_par_total_sort($ju_1,$ju_2,$ju_3,$ju_4);
					$total_sum_ju=$ju_1+$ju_2+$ju_3+$ju_4;
					$zong_score=$lun_1+$lun_2+$lun_3+$lun_4;
					$res=M()->query("update tbl_baofen set total_sum_ju='".$total_sum_ju."',zong_score='".$zong_score."' ".$up_sql."  where event_user_id='".$baofen[0]['event_user_id']."' ".$sub_fenzhan_sql."  ");
					
					//echo "update tbl_baofen set total_sum_ju='".$total_sum_ju."',zong_score='".$zong_score."' ".$up_sql."  where event_user_id='".$baofen[0]['event_user_id']."' ".$sub_fenzhan_sql."  ";
					//echo "<hr>";
					
				} 	
				
			}
			
			//未打球排名成绩初始化	
			$sql="update tbl_baofen set total_ju_par=1000 where cave_1=0 and cave_2=0  and cave_3=0  and cave_4=0  and cave_5=0  and cave_6=0  and cave_7=0 and cave_8=0 and cave_9=0 and cave_1=0  and cave_10=0  and cave_11=0  and cave_12=0  and cave_13=0  and cave_14=0  and cave_15=0  and cave_16=0  and cave_17=0  and cave_18=0 and fenzhan_id='".$fenzhan_id."' ";
			$res=M()->query($sql);
			//更新比赛状态 	
			$res=M()->query("update tbl_baofen set is_end=1 where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and total_score<999  and fenzhan_id='".$fenzhan_id."' ");
			
 
			$fzt=post('fenzu_id');
			$fzt1 = $fzt+1; 
			//$this->success("保存成功",U('field/public/login'));
			//echo "<hr>ok";
			//header ( "Location: baofen.php?ac=ndupdate&fzt=$fzt1&qc_id=".$_POST['qc_id']."&field_id=".$_POST['qc_id']." " );
			header ( "Location:".U('wap/baofen/baofen_big',array('fenzhan_id'=>$fenzhan_id))."" );
		}
		else
		{
			//print_r($_POST);
		}
		
		
		
	}	
	
	
	
	
	public function baofen_save_action()
	{ 
		
		$arr_b =  $_POST['userdk'];

		$fenzhan_id = post('fenzhan_id');
		$lun = post('lun');

		
		if($arr_b)
		{ 
			$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$fenzhan_id."' ")->find();
			$event_id=$fenzhan_info['event_id'];
			$field_id=$fenzhan_info['field_id'];
			$fenzhan_a=$fenzhan_info['fenzhan_a'];
			$fenzhan_b=$fenzhan_info['fenzhan_b'];
			
			//下级分站
			if($fenzhan_info['parent_id'])
			{
				$parent_fenzhan_id=$fenzhan_info['parent_id'];
			}
			else
			{
				$parent_fenzhan_id=$fenzhan_id;
			}
			$sub_arr=array();
			$sub_arr[]=$parent_fenzhan_id;
			$sub_fenzhan=M()->query("select fenzhan_id from tbl_fenzhan where parent_id='".$parent_fenzhan_id."' and event_id='".$event_id."' ");
			for($i=0; $i<count($sub_fenzhan); $i++)
			{
				$sub_arr[]=$sub_fenzhan[$i]['fenzhan_id'];
			}
			
			
			if(count($sub_arr)>=1)
			{
				$sub_fenzhan_sql =" and ( ";
				for($i=0; $i<count($sub_arr); $i++)
				{
					if(count($sub_arr)-$i==1)
					{
						$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$i]."' ";
					}
					else
					{
						$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$i]."' or ";
					}
					
				}
				$sub_fenzhan_sql .=" )";
			}
			else
			{
				$sub_fenzhan_sql=" and (event_id='".$event_id."') ";
			}
			
			
			
			$avs=0;
			foreach($arr_b as $key=>$value)
			{

				if($event_id && $field_id)
				{
					//$qc_par_result=M()->query("select fenzhan_a,fenzhan_b from tbl_fenzhan where field_id='".$field_id."' "); 
					$par = explode(',',$fenzhan_a.','.$fenzhan_b);
					$POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
					$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
					$PTL = $POUT + $PIN; 
					$ttt=0;
					$total_score=0;
					foreach($value as $k=>$var)
					{
						// echo "$k=>$var<br>";
						if($k==30)
						{
							$sql_sets['thru'] = "`thru`='".$var."'";
						}
						else if($k==31)
						{	
							$sql_sets['status'] = "`status`='".$var."'";
						}
						else if($k==32)
						{	
							$sql_sets['jiadong_status'] = "`jiadong_status`='".$var."'";
						}
						else
						{
	
							$sql_sets['cave_' . $k] = "`cave_".$k."`='".$var."'";
							
						}

							$data['cave_' . $k] = $var;

							
						if($var>=0 && $k<30)
						{
							$total_score=$total_score+$var;
						}
				 
						//跳过
						if($var ==-3)
						{ 
							$ttt=999;
						}
						//DQ
						else if($var==-1)
						{
							$ttt=1000;
					
						}
						//取消
						else if($var ==-2)
						{
							$ttt=1001;
						}
						else
						{
							
						}
					}
					
					
					//插入更新操作 18洞成绩
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."'");
					$data ['baofen_id'] = $key;
					//$data ['lun'] = $lun; 
					$data ['event_id'] =  $baofen[0]['event_id'];
					$data ['fenzhan_id'] = $baofen[0]['fenzhan_id'];
					$data ['field_id'] = $field_id; 
					$data ['total_score'] = $total_score;  
					if($baofen[0]['baofen_id'])
					{
						$sql = "update tbl_baofen set ".(implode ( " , ",$sql_sets ))." where baofen_id='".$key."' ";
					} 
					
					/*
					echo "<hr>";	
					echo $sql;
					echo "<hr>";
					*/
					$rs = M()->query($sql);

					//统计总分
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."'");
					$avcave=0;
					for($i = 1; $i <= 18; $i ++)
					{
						if($baofen[0]['cave_'.$i]>0)
						{
							$avcave+=Gpar($baofen[0]['cave_'.$i],$par[$i-1]);
						}
					}
					$sql="update tbl_baofen set total_ju_par=$avcave where baofen_id='".$key."'"; 
					//echo $sql;
					//echo "<hr>";
					$res=M()->query($sql);
			
					$arrypar = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
					$i=0; 
						
					$row=M()->query("select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin from tbl_baofen where baofen_id='".$key."' ");
					for($i = 1; $i <= 21; $i ++) 
					{
						if ($i == '10')
						{
							$sdata [$i] = $row[0]['lout'];
						} 
						elseif ($i == '20')
						{
							$sdata [$i] = $row[0]['lin'];
						}
						elseif ($i == '21')
						{
							$sdata [$i] = $total_score;
						}
						elseif ($i > 9)
						{
							$sdata [$i] = $row[0]['cave_' . ($i - 1)];
						}
						else
						{
							$sdata [$i] = $row[0]['cave_' . $i];
						}
						
						if($sdata [$i]==0)
						{
							$sdata [$i]='-';
						}
					
					} 
					//初始化
					$total_eagle = 0;
					//birdie  
					$total_birdie = 0;
					//E  
					$total_evenpar = 0;
					//bogi  
					$total_bogi = 0;
					//doubles 
					$total_doubles = 0;
					
					for($i = 1; $i <= 21; $i ++) 
					{
						
						if($sdata [$i]!=0 )
						{										
							$data1 [$i] = Getpar ( $sdata [$i] - $par [$i - 1] );
							$dtl [$i] =  $sdata [$i] - $par [$i - 1] ;
							if($i != '10' && $i != '20' && $i != '21')
							{
								//eagle
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '-2' ? $total_eagle ++ : '';
								//birdie 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '-1' ? $total_birdie ++ : '';
								//E 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == 'E' ? $total_evenpar ++ : '';
								//bogi 
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '+1' ? $total_bogi ++ : '';
								//doubles
								Getpar ( $sdata [$i] - $par [$i - 1] ) == '+2' ? $total_doubles ++ : '';
							}
						}
						else
						{
							$data1[$i] = '-';
							$dtl[$i]='-';
						}
						
					}   
					$data1 [10] = $dtl [1] + $dtl [2] + $dtl [3] + $dtl [4] + $dtl [5] + $dtl [6] + $dtl [7] + $dtl [8]+ $dtl [9];
					$data1 [20] = $dtl [11] + $dtl [12] + $dtl [13] + $dtl [14] + $dtl [15] + $dtl [16] + $dtl [17]+ $dtl [18]+ $dtl [19];
					$data1 [21] = $data1 [10] +$data1 [20] ;
					
					$str = implode ( '|', $sdata );
					$str1 = implode ( '|', $data1 ); 
					$total_avepushs = floor ( $total_score / 18 ); 
					
					
			
					$arry ['par'] = "`par`='".$arrypar."'";	
					$arry ['pars'] = "`pars`='".$str1."'";	
					$arry ['score'] = "`score`='".$str."'";	
					$arry ['total_score'] = "`total_score`='".$sdata[21]."'";	
					
					$arry ['total_avepushs'] = "`total_avepushs`='".$total_avepushs."'";	
					$arry ['total_eagle'] = "`total_eagle`='".$total_eagle."'";	
					$arry ['total_birdie'] = "`total_birdie`='".$total_birdie."'";	
					$arry ['total_evenpar'] = "`total_evenpar`='".$total_evenpar."'";	
					$arry ['total_bogi'] = "`total_bogi`='".$total_bogi."'";	
					$arry ['total_doubles'] = "`total_doubles`='".$total_doubles."'";	 
					$arry ['total_ju_par'] = "`total_ju_par`='".$avcave."'";	 
					$arry ['total_sum_ju'] = "`total_sum_ju`='".$total_sum_ju."'";	 
					$arry ['zong_score'] = "`zong_score`='".$zong_score."'";
				
					 
					$res=M()->query("update tbl_baofen set ".(implode(",",$arry))." where baofen_id='".$key."' ");
					//echo "update tbl_baofen set ".(implode(",",$arry))." where baofen_id='".$key."' ";
					//echo "<hr>";
					
				
						 
					//更新状态
					if($ttt)
					{
						$sql_sets ['total_score'] = "`total_score`='".$ttt."'";	
						$sql_sets ['is_end'] = "`is_end`='0'";	
						$sql_sets ['total_ju_par'] = "`total_ju_par`='1000'";	
						$res=M()->query("update tbl_baofen set ".(implode(",",$sql_sets))." where baofen_id='".$key."'");
						//echo "update tbl_baofen set ".(implode(",",$sql_sets))." where uid='".$key."' ";
						//echo "<hr>";
					}
					
					
					//更新总分 0828
					$res=M()->query("update tbl_baofen set total_score=（cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18） "); 
					 
					
					
					//多轮成绩更新
					$lun_num = M()->query("select max(lun) as lun_num from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and event_id<>0 and source='ndong' limit 1 ");
					for($i=0; $i<$lun_num[0]['lun_num']; $i++)
					{
						$lun=$i+1;
						if($baofen[0]['uid'])
						{
							$lun_info = M()->query("select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and uid='".$baofen[0]['uid']."' and lun='".$lun."' and source='ndong' order by dateline asc ");
						}
						else
						{
							$lun_info = M()->query("select baofen_id,sid,uid,total_score,score,par,total_ju_par,to_days(FROM_UNIXTIME(dateline))-to_days(now()) as tianshu from tbl_baofen where 1=1 ".$sub_fenzhan_sql." and event_user_id='".$baofen[0]['event_user_id']."' and lun='".$lun."' and source='ndong' order by dateline asc ");
						}
						
						if($lun==1)
						{
							$ju_1=$lun_info[0]['total_ju_par'];
							$lun_1=$lun_info[0]['total_score'];
							if($ju_1>900)
							{
								$ju_1=0;
							}
							if($lun_1>900)
							{
								$lun_1=0;
							}
							
							$total_ju_par1=$lun_info[0]['total_ju_par'];
						}
						if($lun==2)
						{
							$ju_2=$lun_info[0]['total_ju_par'];
							$lun_2=$lun_info[0]['total_score'];
							if($ju_2>900)
							{
								$ju_2=0;
							}
							if($lun_2>900)
							{
								$lun_2=0;
							}
							
							$total_ju_par2=$lun_info[0]['total_ju_par'];
							
						}
						
						if($lun==3)
						{
							$ju_3=$lun_info[0]['total_ju_par'];
							$lun_3=$lun_info[0]['total_score'];
							if($ju_3>900)
							{
								$ju_3=0;
							}
							if($lun_3>900)
							{
								$lun_3=0;
							}
							$total_ju_par3=$lun_info[0]['total_ju_par'];
						}
						
						if($lun==4)
						{
							$ju_4=$lun_info[0]['total_ju_par'];
							$lun_4=$lun_info[0]['total_score'];
							if($ju_4>900)
							{
								$ju_4=0;
							}
							if($lun_4>900)
							{
								$lun_4=0;
							}
						}
					
					
					}
					
					$up_sql="";
					if($total_ju_par1)
					{
						$up_sql .=" ,total_ju_par1='".$total_ju_par1."' ";
					}
					
					if($total_ju_par2)
					{
						$up_sql .=" ,total_ju_par2='".$total_ju_par2."' ";
					}
					
					if($total_ju_par3)
					{
						$up_sql .=" ,total_ju_par3='".$total_ju_par3."' ";
					}
					
					
		
					//$total_sum_ju=get_ju_par_total_sort($ju_1,$ju_2,$ju_3,$ju_4);
					$total_sum_ju=$ju_1+$ju_2+$ju_3+$ju_4;
					$zong_score=$lun_1+$lun_2+$lun_3+$lun_4;
					$res=M()->query("update tbl_baofen set total_sum_ju='".$total_sum_ju."',zong_score='".$zong_score."' ".$up_sql."  where event_user_id='".$baofen[0]['event_user_id']."' ".$sub_fenzhan_sql."  ");
					
					//echo "update tbl_baofen set total_sum_ju='".$total_sum_ju."',zong_score='".$zong_score."' ".$up_sql."  where event_user_id='".$baofen[0]['event_user_id']."' ".$sub_fenzhan_sql."  ";
					//echo "<hr>";
					
					

					
				} 	
				
			}
			
			//未打球排名成绩初始化	
			$sql="update tbl_baofen set total_ju_par=1000 where cave_1=0 and cave_2=0  and cave_3=0  and cave_4=0  and cave_5=0  and cave_6=0  and cave_7=0 and cave_8=0 and cave_9=0 and cave_1=0  and cave_10=0  and cave_11=0  and cave_12=0  and cave_13=0  and cave_14=0  and cave_15=0  and cave_16=0  and cave_17=0  and cave_18=0 and fenzhan_id='".$fenzhan_id."' ";
			$res=M()->query($sql);
			if($fenzhan_id==50){
			$res=M()->query("update tbl_baofen set total_sum_ju=total_ju_par1,  zong_score=total_score_lun1 where  fenzhan_id in(51,54,57,60) and total_ju_par=1000");
			$res=M()->query("update tbl_baofen set total_sum_ju=total_ju_par+total_ju_par1,  zong_score=total_score+total_score_lun1 where  fenzhan_id in(51,54,57,60) and total_ju_par<1000");
			$res=M()->query("update tbl_baofen set is_end=1 where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and total_score<999 and fenzhan_id in(51,54,57,60)");
			}else{
			//更新比赛状态 	
			$res=M()->query("update tbl_baofen set is_end=1 where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and total_score<999 ");
			
		}
			$fenzu_list=M()->query("select fenzu_id from tbl_baofen where fenzhan_id='".$fenzhan_id."' group by fenzu_id order by fenzu_id   ");  
			for($i=0; $i<count($fenzu_list); $i++)
			{
				if($fenzu_list[$i]['fenzu_id']==post('fenzu_id') && post('fenzu_id'))
				{
					$next_fenzu_id = $fenzu_list[$i+1]['fenzu_id']; 
					break;
				}
			}
			
			if(!$next_fenzu_id)
			{
				$next_fenzu_id=post('fenzu_id')+1;
			}
			
			//$this->success("保存成功",U('field/public/login'));
			//echo "<hr>ok";
			//header ( "Location: baofen.php?ac=ndupdate&fzt=$fzt1&qc_id=".$_POST['qc_id']."&field_id=".$_POST['qc_id']." " );
			header ( "Location:".U('wap/baofen/baofen',array('fenzhan_id'=>$fenzhan_id,'fenzu_id'=>$next_fenzu_id))."" );
		}
		else
		{
			//print_r($_POST);
		}
		
		
		
		
		
	}	
		
		//距标准杆
function Gpar($cave, $par)
{
	$option = $cave - $par;
	 
	return $option;
}

//距标准杆
function Getpar($cave, $par)
{
	if($cave){
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
	}else
	{
		$dataInfo = 0;
	}
	return $dataInfo;
}
 

function get_ju_par_total_sort($ju_1,$ju_2,$ju_3,$ju_4,$ju_5=0)
{
	$total=$ju_1+$ju_2+$ju_3+$ju_4+$ju_5;
	return $total;

}
 
 
function get_ju_par_total_view($ju_1,$ju_2,$ju_3,$ju_4,$ju_5=0)
{

	if($ju_1>900)
	{
		$ju_1=0;
	}
	if($ju_2>900)
	{
		$ju_2=0;
	}
	if($ju_3>900)
	{
		$ju_3=0;
	}
	if($ju_4>900)
	{
		$ju_4=0;
	}
	if($ju_5>900)
	{
		$ju_5=0;
	}

	$total=$ju_1+$ju_2+$ju_3+$ju_4+$ju_5;
	return $total;

}
 
function get_zong_score_view($lun_1,$lun_2,$lun_3,$lun_4,$lun_5=0)
{
	if($lun_1>900)
	{
		$lun_1=0;
	}
	if($lun_2>900)
	{
		$lun_2=0;
	}
	if($lun_3>900)
	{
		$lun_3=0;
	}
	if($lun_4>900)
	{
		$lun_4=0;
	}
	if($lun_5>900)
	{
		$lun_5=0;
	}

	$total=$lun_1+$lun_2+$lun_3+$lun_4+$lun_5;
	return $total;

}
 
 
 

}
?>