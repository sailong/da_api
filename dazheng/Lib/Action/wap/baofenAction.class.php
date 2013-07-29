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
			}
			else
			{
				$fenzu_id=$fenzu_list['item'][0]['fenzu_id'];
			}
			 
			$fenzu_user=M()->query("select * from tbl_baofen where fenzhan_id='".$fenzhan_id."' and fenzu_id='".$fenzu_id."' ");  
			
			
			$this->assign("fenzu_user",$fenzu_user);
			
			$this->display();
		}
		else
		{
			echo "分站不存在";
		}
	}
	public function bf()
	{
		$fenzhan_id=get("fenzhan_id");
		if(!$fenzhan_id)
		{
			$fenzhan_id=$_SESSION['fenzhan_id'];
		}
		 
		
		
		if(!$_SESSION['baofen_user_id'])
		{	
			echo "<script>location='".U('wap/baofen/login',array('fenzhan_id'=>$fenzhan_id,'lun'=>$lun))."';</script>";
			exit;
			//$this->error("请登录",U('field/public/login'));
		}
		
		
		$this->assign('fenzhan_id',$fenzhan_id);
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
			}
			else
			{
				$fenzu_id=$fenzu_list['item'][0]['fenzu_id'];
			}
			 
			$fenzu_user=M()->query("select a.*,b.username from tbl_baofen as a LEFT JOIN pre_common_member as b on a.uid=b.uid  where a.fenzhan_id='".$fenzhan_id."' ");  
			
			$this->assign("fenzu_user",$fenzu_user);
			
			$this->display();
		}
		else
		{
			echo "分站不存在";
		}
	}
	
	public function baofen_save_action()
	{
		$arra = $_POST['userdk'];
		$arr_b=$arra;
		$arra = post('userdk');

		$fenzhan_id = post('fenzhan_id');
		
		if($arr_b)
		{ 
			$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$fenzhan_id."' ")->find();
			$event_id=$fenzhan_info['event_id'];
			$fenzhan_id=$fenzhan_info['fenzhan_id'];
			
			 
			$avs=0; 
			foreach( $arr_b as $key => $value )
			{
			 
				if($fenzhan_id)
				{
				 
					$qc_par_result=M()->query("select fenzhan_a,fenzhan_b,fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."' "); 
					$par = explode(',',$qc_par_result[0]['fenzhan_a'].','.$qc_par_result[0]['fenzhan_b'] );
					$lun =$qc_par_result[0]['fenzhan_lun']; 
				 $POUT = $par [0] + $par [1] + $par [2] + $par [3] + $par [4] + $par [5] + $par [6] + $par [7] + $par [8];
				$PIN = $par [9] + $par [10] + $par [11] + $par [12] + $par [13] + $par [14] + $par [15] + $par [16] + $par [17];
				$PTL = $POUT + $PIN; 
					$ttt=0;
					$total_score=0;
					foreach($value as $k=>$var)
					{
						// echo "$k=>$var<br>";
						$sql_sets['cave_' . $k] = "`cave_".$k."`='".$var."'";
						$data['cave_' . $k] = $var;
						
						if($var>=0)
						{
							$total_score=$total_score+$var;
						}
						
				 
						//跳过
						if($var ==-3)
						{ 
							$ttt=999; 
						}
						//DQ
						if ($var==-1)
						{
							$ttt=1000; 
						}
						//取消
						if($var ==-2)
						{
							$ttt=1001; 
						}
					}
					
					$sql_sets['lun'] = "`lun`='".$lun."'";
					//插入更新操作 18洞成绩
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'");
					$data ['baofen_id'] = $key;
					$data ['lun'] = $lun; 
					$data ['event_id'] = $event_id;
					$data ['fenzhan_id'] = $fenzhan_id;
					$data ['field_id'] = $field_id; 
					//$data ['total_score'] = $total_score;   
					
					if($baofen[0]['baofen_id'])
					{
						$sql = "update tbl_baofen set ".(implode ( " , ",$sql_sets ))." where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'  ";
						 
					}  
					echo "<hr>";	
					echo $sql;
					echo "<hr>"; 
					
					
					$rs = M()->query($sql);

					//统计总分
					$baofen=M()->query("select * from tbl_baofen where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'");
					$avcave=0;
					for($i = 1; $i <= 18; $i ++)
					{ 
						if($baofen[0]['cave_'.$i]>0)
						{
							$avcave+=Gpar($baofen[0]['cave_'.$i],$par[$i-1]);
						}
					}
					$sql="update tbl_baofen set total_ju_par=$avcave where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'"; 
					$res=M()->query($sql);
					
			$arrypar = $par [0] . '|' . $par [1] . '|' . $par [2] . '|' . $par [3] . '|' . $par [4] . '|' . $par [5] . '|' . $par [6] . '|' . $par [7] . '|' . $par [8] . '|' . $POUT . '|' . $par [9] . '|' . $par [10] . '|' . $par [11] . '|' . $par [12] . '|' . $par [13] . '|' . $par [14] . '|' . $par [15] . '|' . $par [16] . '|' . $par [17] . '|' . $PIN . '|' . $PTL;
		$i=0;
					
					
			$row=M()->query("select *,(cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9) as lout,(cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17 +cave_18) as lin   from tbl_baofen where baofen_id='".$key."' ");
					for($i = 1; $i <= 21; $i ++) {
					if ($i == '10') {
						$data [$i] = $row[0]['lout'];
					} elseif ($i == '20') {
						$data [$i] = $row[0]['lin'];
					} elseif ($i == '21') {
						$data [$i] = $total_score;
					} elseif ($i > 9) {
						$data [$i] = $row[0]['cave_' . ($i - 1)];
					} else {
						$data [$i] = $row[0]['cave_' . $i];
					}
				
				}
				$par = explode ( '|', $arry ['par'] );
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
				
				for($i = 1; $i <= 21; $i ++) {
					$data1 [$i] = Getpar ( $data [$i] - $par [$i - 1] );
					if ($i != '10' && $i != '20' && $i != '21') {
						//eagle
						Getpar ( $data [$i] - $par [$i - 1] ) == '-2' ? $total_eagle ++ : '';
						//birdie 
						Getpar ( $data [$i] - $par [$i - 1] ) == '-1' ? $total_birdie ++ : '';
						//E 
						Getpar ( $data [$i] - $par [$i - 1] ) == 'E' ? $total_evenpar ++ : '';
						//bogi 
						Getpar ( $data [$i] - $par [$i - 1] ) == '+1' ? $total_bogi ++ : '';
						//doubles
						Getpar ( $data [$i] - $par [$i - 1] ) == '+2' ? $total_doubles ++ : '';
					}
				} 
				 $str = implode ( '|', $data );
				$str1 = implode ( '|', $data1 ); 
				$total_avepushs = floor ( $total_score / 18 ); 
				
				$arry ['par'] = "`par`='".$arrypar."'";	
				$arry ['pars'] = "`pars`='".$str1."'";	
				$arry ['score'] = "`score`='".$str."'";	
				$arry ['total_pushs'] = "`total_score`='".$total_score."'";	
				$arry ['total_avepushs'] = "`total_avepushs`='".$total_avepushs."'";	
				$arry ['total_eagle'] = "`total_eagle`='".$total_eagle."'";	
				$arry ['total_birdie'] = "`total_birdie`='".$total_birdie."'";	
				$arry ['total_evenpar'] = "`total_evenpar`='".$total_evenpar."'";	
				$arry ['total_bogi'] = "`total_bogi`='".$total_bogi."'";	
				$arry ['total_doubles'] = "`total_doubles`='".$total_doubles."'";	 
				 
			 $res=M()->query("update tbl_baofen set ".(implode(",",$arry))." where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'");
						 
					//更新状态
					if($ttt)
					{
						$sql_sets ['total_score'] = "`total_score`='".$ttt."'";	
						$sql_sets ['is_end'] = "`is_end`='0'";	
						$sql_sets ['total_ju_par'] = "`total_ju_par`='1000'";	
						$res=M()->query("update tbl_baofen set ".(implode(",",$sql_sets))." where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'");
						//echo "update tbl_baofen set ".(implode(",",$sql_sets))." where uid='".$key."' and fenzhan_id='".$fenzhan_id."' ";
						//echo "<hr>";
					}
					else
					{ 
						$res=M()->query("update tbl_baofen set total_score=cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18 where baofen_id='".$key."' and fenzhan_id='".$fenzhan_id."'");
						//echo "update tbl_baofen set total_score=cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18 where uid='".$key."' and fenzhan_id='".$fenzhan_id."' ";
					}
				
				} 	
				
			}
			
			//未打球排名成绩初始化	
			$sql="update tbl_baofen set total_ju_par=1000 where cave_1=0 and cave_2=0  and cave_3=0  and cave_4=0  and cave_5=0  and cave_6=0  and cave_7=0 and cave_8=0 and cave_9=0 and cave_1=0  and cave_10=0  and cave_11=0  and cave_12=0  and cave_13=0  and cave_14=0  and cave_15=0  and cave_16=0  and cave_17=0  and cave_18=0 and fenzhan_id='".$fenzhan_id."' ";
			$res=M()->query($sql); 
			//更新比赛状态 	
			$res=M()->query("update tbl_baofen set is_end=1 where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and total_score<999  and fenzhan_id='".$fenzhan_id."' "); 	

			$fzt1 = $_POST['fenzu_id'];
			$fzt1 = $fzt1+1;
		 
			//$this->success("保存成功",U('field/public/login'));
			//echo "<hr>ok";
			//header ( "Location: baofen.php?ac=ndupdate&fzt=$fzt1&qc_id=".$_POST['qc_id']."&field_id=".$_POST['qc_id']." " );
			
			if(post('ac')=='tjbf'){
			header ( "Location:".U('wap/baofen/bf',array('fenzhan_id'=>$fenzhan_id))."" );
			}
			 else
			{	
			header ( "Location:".U('wap/baofen/baofen',array('fenzhan_id'=>$fenzhan_id,'fenzu_id'=>$fzt1))."" );
			}
		}
		
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
			 


		
	}
	
	
	
	
	
	
	

}
?>