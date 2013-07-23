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
					$this->success("登录成功，现在可以报分了",U('wap/baofen/baofen',array('fenzhan_id'=>$fenzhan_id,'lun'=>$lun)));
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
			$fenzu_list=D("fenzu")->fenzu_select_pro(" and fenzhan_id='".$fenzhan_id."' and lun='".$lun."' ",999," fenzu_number asc ");
			$this->assign('fenzu_list',$fenzu_list['item']);
			
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
			
			$fenzu_user=D("fenzu_mingxi")->fenzu_mingxi_select_pro(" and fenzu_id='".$fenzu_id."' and lun='".$lun."' ");
			$this->assign("fenzu_user",$fenzu_user['item']);
			
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
		$lun = post('lun');

		
		
		if($arr_b)
		{
			$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$fenzhan_id."' ")->find();
			$event_id=$fenzhan_info['event_id'];
			$field_id=$fenzhan_info['field_id'];
			
			$avs=0;
			foreach( $arr_b as $key => $value )
			{
			
				if($event_id && $field_id)
				{
				
					$qc_par_result=M()->query("select par from pre_common_field where uid='".$field_id."' "); 
					$par = explode(',',$qc_par_result[0]['par'] );
					
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
					
					//插入更新操作 18洞成绩
					$baofen=M()->query("select * from tbl_baofen where uid='".$key."' and fenzhan_id='".$fenzhan_id."' and lun='".$lun."' ");
					$data ['uid'] = $key;
					$data ['lun'] = $lun;
					$realname=M()->query("select realname from tbl_fenzu_mingxi where uid='".$key."' and fenzhan_id='".$fenzhan_id."'  and lun='".$lun."' ");
					$data ['realname'] = $realname[0]['realname'];
					$data ['event_id'] = $event_id;
					$data ['fenzhan_id'] = $fenzhan_id;
					$data ['field_id'] = $field_id; 
					//$data ['total_score'] = $total_score; 
					if($baofen[0]['baofen_id'])
					{
						$sql = "update tbl_baofen set ".(implode ( " , ",$sql_sets ))." where uid='".$key."' and fenzhan_id='".$fenzhan_id."'  and lun='".$lun."' ";
					}
					else
					{
						$sql = "insert into tbl_baofen (`".implode( "`,`",array_keys($data)). "`) values ('" . implode ( "','", $data ) . "')";
					}
					/*
					echo "<hr>";	
					echo $sql;
					echo "<hr>";
					*/
					$rs = M()->query($sql);

					//统计总分
					$baofen=M()->query("select * from tbl_baofen where uid='".$key."' and fenzhan_id='".$fenzhan_id."' and lun='".$lun."' ");
					$avcave=0;
					for($i = 1; $i <= 18; $i ++)
					{ 
						if($baofen[0]['cave_'.$i]>0)
						{
							$avcave+=Gpar($baofen[0]['cave_'.$i],$par[$i-1]);
						}
					}
					$sql="update tbl_baofen set total_ju_par=$avcave where uid='".$key."' and fenzhan_id='".$fenzhan_id."' and lun='".$lun."' ";
					$res=M()->query($sql);
					
					//更新状态
					if($ttt)
					{
						$sql_sets ['total_score'] = "`total_score`='".$ttt."'";	
						$sql_sets ['is_end'] = "`is_end`='0'";	
						$sql_sets ['total_ju_par'] = "`total_ju_par`='1000'";	
						$res=M()->query("update tbl_baofen set ".(implode(",",$sql_sets))." where uid='".$key."' and fenzhan_id='".$fenzhan_id."'  and lun='".$lun."' ");
						//echo "update tbl_baofen set ".(implode(",",$sql_sets))." where uid='".$key."' and fenzhan_id='".$fenzhan_id."' ";
						//echo "<hr>";
					}
					else
					{ 
						$res=M()->query("update tbl_baofen set total_score=cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18 where uid='".$key."' and fenzhan_id='".$fenzhan_id."' and lun='".$lun."' ");
						//echo "update tbl_baofen set total_score=cave_1+cave_2+cave_3+cave_4+cave_5+cave_6+cave_7+cave_8+cave_9+cave_10+cave_11+cave_12+cave_13+cave_14+cave_15+cave_16+cave_17+cave_18 where uid='".$key."' and fenzhan_id='".$fenzhan_id."' ";
					}
				
				} 	
				
			}
			
			//未打球排名成绩初始化	
			$sql="update tbl_baofen set total_ju_par=1000 where cave_1=0 and cave_2=0  and cave_3=0  and cave_4=0  and cave_5=0  and cave_6=0  and cave_7=0 and cave_8=0 and cave_9=0 and cave_1=0  and cave_10=0  and cave_11=0  and cave_12=0  and cave_13=0  and cave_14=0  and cave_15=0  and cave_16=0  and cave_17=0  and cave_18=0 and fenzhan_id='".$fenzhan_id."' ";
			$res=M()->query($sql); 
			//更新比赛状态 	
			$res=M()->query("update tbl_baofen set isend=1 where cave_1>0 and cave_2>0  and cave_3>0  and cave_4>0  and cave_5>0  and cave_6>0  and cave_7>0  and cave_8>0  and cave_9>0  and cave_10>0  and cave_11>0  and cave_12>0  and cave_13>0  and cave_14>0  and cave_15>0  and cave_16>0  and cave_17>0  and cave_18>0  and total_score<999  and fenzhan_id='".$fenzhan_id."' "); 	

			$fzt1 = $_POST['fzt1'];
			$fzt1 = $fzt1+1;
			if($fzt1>22)
			{
				$fzt1 = 1;
			}
			//$this->success("保存成功",U('field/public/login'));
			//echo "<hr>ok";
			//header ( "Location: baofen.php?ac=ndupdate&fzt=$fzt1&qc_id=".$_POST['qc_id']."&field_id=".$_POST['qc_id']." " );
			header ( "Location:".U('wap/baofen/baofen',array('fenzhan_id'=>$fenzhan_id,'fenzu_id'=>$fenzu_id))."" );
		}
	}
	
	
	
	
	
	
	

}
?>