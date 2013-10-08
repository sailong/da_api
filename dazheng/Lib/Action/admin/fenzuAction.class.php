<?php
/**
 *    #Case		bwvip
 *    #Page		FenzuAction.class.php (赛事分组)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-05-29
 */
class fenzuAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function fenzu()
	{
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
			
		$fenzhan_id=get("fenzhan_id");
		if($fenzhan_id)
		{
			$fenzhan_info=M()->query("select fenzhan_lun from tbl_fenzhan where fenzhan_id='".$fenzhan_id."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
		}
		for($i=0; $i<$fenzhan_lun; $i++)
		{
			$lun_arr[]=$i+1;
		}
		$this->assign('lun_arr',$lun_arr);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$list=D("fenzu")->fenzu_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign('fenzu_on',1);
		$this->assign("page_title","赛事分组");
    	$this->display();
	}

	public function fenzu_add()
	{
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$this->assign("page_title","添加赛事分组");
    	$this->display();
	}

	public function fenzu_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_number"]=post("fenzu_number");
			$data["fenzu_name"]=post("fenzu_name");
			$data["fenzu_tee"]=post("fenzu_tee");
			$data["fenzu_start_time"]=strtotime(post("fenzu_start_time"));
			$data["fenzu_ampm"]=post("fenzu_ampm");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["lun"]=post("lun");
			$data["fenzu_addtime"]=time();
			
			$list=M("fenzu")->add($data);
			$this->success("添加成功",U('admin/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzu/fenzu_add',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}


	public function fenzu_edit()
	{
		if(intval(get("fenzu_id"))>0)
		{
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
			$this->assign('fenzhan',$fenzhan['item']);
		
			$data=M("fenzu")->where("fenzu_id=".intval(get("fenzu_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改赛事分组");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}
	
	
   public function rule()
	{
		 
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		$list=D("fenzu")->fenzu_list_pro(); 
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign('fenzu_on',1);
		$this->assign("page_title","赛事分组");
    	$this->display();
	}

   public function rule_list()
	{
		 
		
	$list=M()->query("select * from tbl_fenzu_rule where event_id='".get("event_id")."' ");	 
		$this->assign("list",$list); 
		$this->assign('fenzu_on',1);
		$this->assign("page_title","赛事分组");
    	$this->display();
	}

	public function fenzu_rule_delete_action()
	{
		if(post("ids"))
		{ 
		
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("fenzu_rule")->where("id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function rule_add_action()
	{
	
		if(M()->autoCheckToken($_POST))
		{
			
			$data["event_id"]=post("event_id"); 
			$data["fenzhan_id"]=post("fenzhan_id"); 
			$fenzhan_info=M()->query("select fenzhan_lun,field_id from tbl_fenzhan where fenzhan_id='".$data["fenzhan_id"]."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
			$field_id=$fenzhan_info[0]['field_id']; 			
			$data["field_id"]=$field_id;				
			$data["fenzhan_lun"]=$fenzhan_lun;  
			
			$data["game_jg_time"]=post("game_jg_time")*60;
			$data["game_starttime"]=strtotime(post("game_starttime"));
			$data["rest_starttime"]=strtotime(post("rest_starttime"));
			$data["rest_endtime"]=strtotime(post("rest_endtime"));
			$data["event_id"]=post("event_id");
			$data["fenzu_rule"]=post("fenzu_rule");
			$data["team_member_num"]=post("team_member_num");
			$data["tee"]=$_POST["tee"]; 
			$data["addtime"]=time();
			 
		 
			$is_fenzu=M()->query("select * from tbl_fenzu_rule where event_id='".$data["event_id"]."' and  fenzhan_id='".$data["fenzhan_id"]."'");
			if($is_fenzu)
			{
				$this->error("该分站下 已经分组 你可以删除 从新分组");
			}
		 
		 
			
			
			if($data['fenzu_rule']==2)
			{
				$orderby="order by event_apply_chadian asc";
			}
			if($data['fenzu_rule']==3)
			{
				$orderby="order by rand() ";
			}
			if($data['fenzu_rule']==4)
			{
				$orderby="order by total_sum_ju asc";
			}
			if($data['fenzu_rule']==5)
			{
				$orderby="order by total_sum_ju desc";
			}
			
			$members_list=array();

			/*分站会员列表*/ 
			$members_list=M()->query("select * from tbl_event_apply where event_id='".$data["event_id"]."' and  fenzhan_id='".$data["fenzhan_id"]."' $orderby");
			if(empty($members_list))
			{
				$this->error("sorry！你的分站暂时还没有会员 请为分组添加相应的会员");
			}

			//算出总组数   总人数/每组人数
			$fz_num         = ceil(count($members_list)/$data['team_member_num']);
			$tee_num        = ceil(count($data['tee']));
			$am_game_time   = $data['rest_starttime'] -$data['game_starttime'];  //上半场开球时间
			$am_kq_num      = ceil($am_game_time /$data['game_jg_time']);      //计算上午能分出多少组  用上午比赛的时间/间隔时间*开球tee数
 
			/*上午分组*/
			$k=$kk=$t=0;
			
			for($j=1;$j<=$tee_num;$j++)
			{
				for($i=1;$i<=$am_kq_num;$i++)
				{
					$k++; 
					$bs_data[$k]['start_time'] = $data['game_starttime'] +  ($kk * $data['game_jg_time']);
					$bs_data[$k]['am_pm']      = 1; //上午

					$num=($k-1)%$tee_num; 
					$bs_data[$k]['kq_tee']     = $data['tee'][$num];

					if($k%2==0)
					{$kk++;} 
				}
				//echo $t;
				$t++; //空口游标 
			}
/*}*/
  
			/*下午分组*/
			$kk=0;$t=0;
			$pm_kq_num=ceil(($fz_num -($am_kq_num*$tee_num))/$tee_num);

			for($j=1;$j<=$tee_num;$j++)
			{
				for($i=1;$i<=$pm_kq_num;$i++)
				{
					$k++;		  
					$bs_data[$k]['start_time'] =  $data['rest_endtime'] +  ($kk * $data['game_jg_time']);
					$bs_data[$k]['am_pm']= 2; //下午
					$num=($k-1)%$tee_num; 
					$bs_data[$k]['kq_tee']     = $data['tee'][$num];
					if($k%2==0)
					{
						$kk++;
					} 
				}
				//echo $t;
				$t++; //空口游标
			   
			}
 

			$i=0; 
			$z=0; 
			foreach($members_list as $rows)
			{
				if($i%$data['team_member_num']==0) $z++;
				$bs_data[$z]['users'][$i]['uid']      = $rows['uid'];
				$bs_data[$z]['users'][$i]['event_user_id']      = $rows['event_user_id'];
				$bs_data[$z]['users'][$i]['lun']      = $fenzhan_lun;
				$bs_data[$z]['users'][$i]['realname'] = $rows['event_apply_realname']; 
				$bs_data[$z]['users'][$i]['event_apply_chadian']     = $rows['event_apply_chadian'];
				 
			//$bs_data[$z]['users']= sortByCol($bs_data[$z]['users'], 'chadian', SORT_ASC);  
				$i++;
			} 
  
 
			//array_multisort($start_time, SORT_ASC,$bs_data); //对相同差点的人 从新排序   
			$insert_data['fenzhan_id']    = $data['fenzhan_id']; 
			$insert_data['event_id']   = $data['event_id']; 
			$insert_data['addtime']   = time();  
			foreach($bs_data as $key=>$value)
			{
				foreach($value['users'] as $k =>$v)
				{
					$v['start_time'] = $value['start_time'];
					$v['am_pm']      = $value['am_pm'];
					$v['tee']        = $value['kq_tee']; 
					$v['fenzu_id']= $key;
					$rows = array_merge($v,$insert_data); 
					$list=M("baofen")->add($rows);
				}
			} 
			$members_list=M()->query("select * from tbl_event_apply where event_id='".$data['event_id']."' and  fenzhan_id='".$data['fenzhan_id']."' $orderby");
			$list=M("fenzu_rule")->add($data);
			//print_r($data);
			
	
			//多轮成绩更新
			$s_lun=$fenzhan_lun-1;
			if(post("fenzhan_id") && post("event_id") && $s_lun)
			{
				$baofen=M()->query("select baofen_id,uid,event_user_id,event_id from tbl_baofen where fenzhan_id='".post("fenzhan_id")."' ");
				for($ii=0; $ii<count($baofen); $ii++)
				{
					$last=M()->query("select baofen_id,status,zong_score,total_sum_ju from tbl_baofen where event_id='".post("event_id")."' and event_user_id='".$baofen[$ii]['event_user_id']."' and lun='".$s_lun."' order by lun desc,addtime desc limit 1 ");
					//print_r($last);
					
					$data_b['baofen_id']=$baofen[$ii]['baofen_id'];
					$data_b['status']=$last[0]['status'];
					$data_b['zong_score']=$last[0]['zong_score'];
					$data_b['total_sum_ju']=$last[0]['total_sum_ju'];
					$res=M('baofen')->save($data_b);
					print_r($data_b);
					echo "<hr>";

				}
			}
		
			
			
			$this->success("添加成功",U('admin/fenzu/rule',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
			

		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzu/rule',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
	
		
		
		
 

	}
	
	public function tiaopei()
	{
		 
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		
		$data=M()->query("SELECT baofen_id,uid,realname,fenzu_id,tee,start_time   from tbl_baofen  where baofen_id='".get("baofen_id")."'");  
		 
		$this->assign("data",$data[0]); 
		
		$this->assign('fenzhan_user_on',1);
		
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".get("event_id")."' ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		
		$this->assign("event_id",intval(get("event_id")));
		$this->assign("page_title","赛事分组");
    	$this->display();
	}
	
	
	public function tiaopei_edit()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_id"]=post("fenzu_id");
			$data["baofen_id"]=post("baofen_id");
			$data["uid"]=post("uid");
			$data["realname"]=post("realname");
			$data["start_time"]=strtotime(post("start_time"));
			$data["tee"]=post("tee");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$list=M("baofen")->save($data); 
			$this->success("修改成功",U('admin/fenzhan/fenzhan_user',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzhan/fenzhan_user',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}
	
	public function tiaopei_add()
	{ 
       $data=M()->query("SELECT baofen_id,uid,realname,fenzu_id,tee,start_time   from tbl_baofen  where baofen_id='".get("baofen_id")."'");  
		 
		$this->assign("data",$data[0]); 
		
		$this->assign('fenzhan_user_on',1); 
		
	    $this->assign('start_time',time());
		
		$this->assign("event_id",intval(get("event_id")));
		$this->assign("page_title","赛事分组");
    	$this->display();
	}
	public function tiaopei_addaction()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_id"]=post("fenzu_id"); 
			$data["uid"]=post("uid");
			$data["realname"]=post("realname");
			$data["start_time"]=strtotime(post("start_time"));
			$data["tee"]=post("tee");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$fenzhan_info=M()->query("select fenzhan_lun,field_id from tbl_fenzhan where fenzhan_id='".$data["fenzhan_id"]."'");
			$fenzhan_lun=$fenzhan_info[0]['fenzhan_lun'];
			$field_id=$fenzhan_info[0]['field_id']; 
			
			$data["field_id"]=$field_id;  
			$data["lun"]=$fenzhan_lun;  
			$data["addtime"]=time();  
			$list=M("baofen")->add($data); 
			$this->success("添加成功",U('admin/fenzhan/fenzhan_user',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzhan/fenzhan_user',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}
	public function daochu()
	{
		$fenzhan_id=get('fenzhan_id'); 
		$event_id=get('event_id'); 
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find(); 
		$excelname=$event_info['event_name'];
	   
		 if($fenzhan_id)
		{
		$arr=M()->query("SELECT uid,realname,fenzu_id,tee,FROM_UNIXTIME(start_time, '%Y-%m-%d  %H:%i:%S')   from tbl_baofen  where fenzhan_id='".$fenzhan_id."'"); 
		
	 	$data=M("fenzhan")->where("fenzhan_id=".$fenzhan_id)->find(); 
		$excelname.='-'.$data['fenzhan_name'];
		}else
		{		
	    $arr=M()->query("SELECT uid,realname,fenzu_id,tee,FROM_UNIXTIME(start_time, '%Y-%m-%d  %H:%i:%S')   from tbl_baofen  where event_id='".$event_id."'");  
		}
	    $excelname=trim($excelname);
		 //echo $data['fenzhan_name'];exit;
		// generate file (constructor parameters are optional)
		/*调用excel类*/
		include 'excelclass.php';
		$xls = new Excel_XML('UTF-8', false,'第1页');
		$xls->addArray($arr);
		$xls->generateXML($excelname);
		 
		
		 
	}
	public function fenzu_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fenzu_id"]=post("fenzu_id");
			$data["fenzu_number"]=post("fenzu_number");
			$data["fenzu_name"]=post("fenzu_name");
			$data["fenzu_tee"]=post("fenzu_tee");
			$data["fenzu_start_time"]=strtotime(post("fenzu_start_time"));
			$data["fenzu_ampm"]=post("fenzu_ampm");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["lun"]=post("lun");
			
			$list=M("fenzu")->save($data);
			$this->success("修改成功",U('admin/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzu/fenzu',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data['fenzhan_id'])));
		}

	}

	public function fenzu_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("fenzu")->where("fenzu_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function fenzu_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_fenzu set fenzu_state=1 where fenzu_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^审核成功";
			}
			else
			{
				echo "error^审核失败";
			}			
			
		}
	}

	public function fenzu_detail()
	{
		if(intval(get("fenzu_id"))>0)
		{
			$data=M("fenzu")->where("fenzu_id=".intval(get("fenzu_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["fenzu_name"]."赛事分组");
				$this->display();
			}
			else
			{
				$this->error("您该问的信息不存在");	
			}
			
		}
		else
		{
			$this->error("您该问的信息不存在");
		}

	}


	

}

?>