<?php
/**
 *    #Case		bwvip
 *    #Page		Event_applyAction.class.php (赛事报名)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class event_applyAction extends AdminAuthAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function event_apply()
	{
		$this->assign('event_apply_on',1);
		
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		$this->assign('event_type',$event_info['event_type']);
		
		$fenzhan_id=get("fenzhan_id");
		$fenzhan=M('fenzhan')->where("event_id='".get("event_id")."'")->select();
		$fenzhan_info = array();
		foreach($fenzhan as $key=>$val) {
			$fenzhan_info[$val['fenzhan_id']] = $val;
			$default_fenzhan_id = $val['fenzhan_id'];
		}
		unset($fenzhan);
		
		$this->assign('fenzhan',$fenzhan_info);
		
		if(empty($fenzhan_id))
		{
			$fenzhan_id = $default_fenzhan_id;
		}
		
		$this->assign("fenzhan_id",$fenzhan_id);
		
		$arr=D("event_apply")->event_apply_list_pro(" and fenzhan_id='{$fenzhan_id}'");
		
		
		$parent_list = array();
		$child_list = array();
		
		foreach($arr["item"] as $key=>$val)
		{
			if($val['parent_id']=='0')
			{
				$parent_list[$val['event_apply_id']] = $val;
			}else
			{
				$child_list[$val['event_apply_id']] = $val;
			}
		}
		foreach($parent_list as $key=>$val)
		{
			//$parent_list[$key]['child_list'] =array();
			$child_list_tmp = array();
			foreach($child_list as $key1=>$val1)
			{
				if($val1['parent_id'] == $key)
				{
					$child_list_tmp[$key1] = $val1;
				}
				
			}
			if(empty($child_list_tmp)){
				$child_list_tmp = null;
			}
			$parent_list[$key]['child_list'] = $child_list_tmp;
		}
		
		unset($arr["item"]);
		/* echo '<pre>';
		var_dump($parent_list);die; */
		
		$this->assign("list1",$parent_list);
		//$this->assign("list",$arr["item"]);
		$this->assign("pages",$arr["pages"]);
		$this->assign("total",$arr["total"]);

		$this->assign("page_title","赛事报名");
    	$this->display();
	}

	public function event_apply_add()
	{
		$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
		$this->assign('event',$event['item']);
		
		$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and field_uid='".$_SESSION['field_uid']."'  ");
		$this->assign('fenzhan',$fenzhan['item']);
		
		
		$this->assign("page_title","添加赛事报名");
    	$this->display();
	}

	public function event_apply_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_id"]=post("event_id");
			$data["uid"]=post("uid");
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			$data["event_apply_addtime"]=time();
			
			$arr=M("event_apply")->add($data);
		
			$this->success("添加成功",U('admin/event_apply/event_apply',array('event_id'=>$data['event_id'])));
			
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function event_apply_edit()
	{
		if(intval(get("event_apply_id"))>0)
		{
			$data=M("event_apply")->where("event_apply_id=".intval(get("event_apply_id")))->find();
			$this->assign("data",$data);
			
			
			$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
			$this->assign('event',$event['item']);
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and event_id='".$data['event_id']."'  ");
			$this->assign('fenzhan',$fenzhan['item']);
			
			$apply_list=D('event_apply')->event_apply_select_pro(" and fenzhan_id='".$data['fenzhan_id']."' and parent_id=0 ");
			$this->assign('event_apply_list',$apply_list['item']);
			
			
			$this->assign("page_title","修改赛事报名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function event_apply_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
	
			$data["event_apply_id"]=post("event_apply_id");
			$data["event_id"]=post("event_id");
			$data["fenzhan_id"]=post("fenzhan_id");
			$data["uid"]=post("uid");
			$data["parent_id"]=post("parent_id");
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			
			$arr=M("event_apply")->save($data);
			
			$this->success("修改成功",U('admin/event_apply/event_apply',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data["fenzhan_id"],'event_apply_state'=>1)));
		
		}
		else
		{
			$this->error("参数错误或来路非法",U('admin/event_apply/event_apply',array('event_id'=>$data['event_id'],'fenzhan_id'=>$data["fenzhan_id"],'event_apply_state'=>1)));
		}

	}

	public function event_apply_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("event_apply")->where("event_apply_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function event_apply_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event_apply set event_apply_state=1 where event_apply_id=".$ids_arr[$i]." ");
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
	
	
	public function event_apply_no_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event_apply set event_apply_state=2 where event_apply_id=".$ids_arr[$i]." ");
			}
			if($res)
			{
				echo "succeed^操作成功";
			}
			else
			{
				echo "error^操作失败";
			}			
			
		}
	}

	public function event_apply_detail()
	{
		if(intval(get("event_apply_id"))>0)
		{
			$data=M("event_apply")->where("event_apply_id=".intval(get("event_apply_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["event_apply_name"]."赛事报名");
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
	
	
	
	
	public function batch_apply_to_baofen_action()
	{
		if(get('event_id'))
		{
			$arr=explode(",",post('ids'));
	
			for($i=0; $i<count($arr); $i++)
			{
				
				$apply_info=M('event_apply')->where(" event_apply_id='".$arr[$i]."' ")->find();
			
				if($arr[$i] && $apply_info['event_apply_id'])
				{
			
					$fenzhan_info=M("fenzhan")->where(" fenzhan_id='".$apply_info['fenzhan_id']."' ")->find();
					$event_id=$fenzhan_info['event_id'];
					$field_id=$fenzhan_info['field_id'];
					$fenzhan_a=$fenzhan_info['fenzhan_a'];
					$fenzhan_b=$fenzhan_info['fenzhan_b'];
					$fenzhan_lun=$fenzhan_info['fenzhan_lun'];
					$event_info=M("event")->where(" event_id='".$apply_info['event_id']."' ")->field('event_type,event_team_level')->find();
				
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
					for($j=0; $j<count($sub_fenzhan); $j++)
					{
						$sub_arr[]=$sub_fenzhan[$j]['fenzhan_id'];
					}
					
					
					if(count($sub_arr)>=1)
					{
						$sub_fenzhan_sql =" and ( ";
						for($jj=0; $jj<count($sub_arr); $jj++)
						{
							if(count($sub_arr)-$jj==1)
							{
								$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$jj]."' ";
							}
							else
							{
								$sub_fenzhan_sql .=" fenzhan_id='".$sub_arr[$jj]."' or ";
							}
							
						}
						$sub_fenzhan_sql .=" )";
					}
					else
					{
						$sub_fenzhan_sql=" and (event_id='".$event_id."') ";
					}
					
					
					$event_user_info=M("event_user")->where(" event_user_id='".$apply_info['event_user_id']."' ")->find();
					
					$insert_data=array();
					
					
					$insert_data['uid'] = $apply_info['uid']; 
					$insert_data['event_user_id'] = $apply_info['event_user_id']; 
					$insert_data['event_user_team'] = $apply_info['event_user_team']; 
					$insert_data['event_apply_id'] = $apply_info['event_apply_id']; 
					$insert_data['event_apply_parent_id'] = $apply_info['parent_id']; 
					$insert_data['realname'] = $apply_info['event_apply_realname']; 
					
					$insert_data['country'] = $event_user_info['country']; 
					
					$insert_data['event_id']   = $event_id;
					
					$insert_data['event_apply_chadian'] = $apply_info['event_apply_chadian']; 
					$insert_data['lun'] = $fenzhan_info['fenzhan_lun']; 
					$insert_data['fenzhan_id'] = $fenzhan_info['fenzhan_id']; 
					$insert_data['addtime']   = time();  
					$insert_data['dateline']  = time();  
					$insert_data['fenzu_id']  = 1;
					$insert_data['tee']  = 1;
					$insert_data['status']    = 0;
					$insert_data['start_time']   = time();
					
					$score_info=M()->query("select * from tbl_baofen where event_user_id='".$event_user_id."' and zong_score>0 and zong_score<900 ".$sub_fenzhan_sql." limit 1 ");
					if($score_info[0]['baofen_id'])
					{
						$insert_data['zong_score']=$score_info[0]['zong_score'];
						$insert_data['total_sum_ju']=$score_info[0]['total_sum_ju'];
					}
					
		
					$baofen_info=M()->query("select * from tbl_baofen where event_user_id='".$apply_info['event_user_id']."' and fenzhan_id='".$apply_info['fenzhan_id']."' ");
					if(!$baofen_info[0]['baofen_id'])
					{
					
						if($event_info['event_type']=='T')
						{
							if($fenzhan_info['fenzhan_rule']==11 && $apply_info['parent_id']==0)
							{
								$res=M("baofen")->add($insert_data);
						
							}
							else if($fenzhan_info['fenzhan_rule']==42 && $apply_info['parent_id']==0)
							{
								$res=M("baofen")->add($insert_data);
							}
							else if($fenzhan_info['fenzhan_rule']==44 && $apply_info['parent_id']>0)
							{
								$res=M("baofen")->add($insert_data);
							}
							else
							{
								
							}
						}
						else
						{
							$res=M("baofen")->add($insert_data);
						}
						//print_r(M());
					}
					
				}
				

			}
				

			echo "succeed^操作成功";
		}
		else
		{
			echo "error^操作失败";
		}

	}


	

}
?>