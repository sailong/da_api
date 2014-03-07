<?php
/**
 *    #Case		bwvip
 *    #Page		Event_userAction.class.php (赛事用户)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-13
 */
class event_userAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function event_user()
	{
		//echo "sdf/ksdjflsdkf";
		
		$event_id=get('event_id');
		if(get('event_id'))
		{
			$sql =" and event_id='".get('event_id')."'";
		}
		$list=D("event_user")->event_user_list_pro(" and event_user_parent_id=0 ".$sql,150," event_user_addtime desc ");
		$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		$this->assign('event_type',$event_info['event_type']);

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign('event_user_on',1);
		$this->assign('event_id',$event_id);

		$this->assign("page_title","赛事用户");
    	$this->display();
	
	}

	public function event_user_add()
	{
		$event=D('event')->event_select_pro();
		$this->assign('event_list',$event['item']);
		
		$event_user_list=D("event_user")->event_user_select_pro(" and event_id=".get("event_id")." and event_user_parent_id=0  ");
		$this->assign("event_user_list",$event_user_list['item']);
		
		$event_info=M('event')->where("event_id=".get("event_id")." ")->field("event_left_flag,event_right_flag,event_type")->find();
		$team_list[]=$event_info['event_left_flag'];
		$team_list[]=$event_info['event_right_flag'];
		
		
		
		$country_list=select_dict(17,"select");
		$this->assign("country_list",$country_list);
		
		
		
		$this->assign("event_info",$event_info);
		$this->assign("team_list",$team_list);
		$this->assign("page_title","添加赛事用户");
    	$this->display();
	}

	public function event_user_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_id"]=post("event_id");
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["uid"]=post("uid");
			$data["event_user_realname"]=post("event_user_realname");
			$data["event_user_enrealname"]=post("event_user_enrealname");
			$data["event_user_sex"]=post("event_user_sex");
			$data["event_user_card_type"]=post("event_user_card_type");
			$data["event_user_card"]=post("event_user_card");
			$data["event_user_chadian"]=post("event_user_chadian");
			
			$data["event_user_state"]=1;
			
			$data["country"]=post("country");
			
			if(post("event_user_team")!="")
			{
				$data["event_user_team"]=post("event_user_team");
			}
			if(post("event_user_parent_id")!="")
			{
				$data["event_user_parent_id"]=post("event_user_parent_id");
			}
			
			$data["event_user_addtime"]=time();
			
			$list=M("event_user")->add($data);
			$this->success("添加成功",U('admin/event_user/event_user',array('event_id'=>post('event_id'))));
		}
		else
		{
			$this->error("不能重复提交",U('admin/event_user/event_user_add',array('event_id'=>post('event_id'))));
		}

	}


	public function event_user_edit()
	{
		if(intval(get("event_user_id"))>0)
		{
			$data=M("event_user")->where("event_user_id=".intval(get("event_user_id")))->find();
			$this->assign("data",$data);
			
			
			
			$event=D('event')->event_select_pro();
			$this->assign('event_list',$event['item']);
			
			$country_list=select_dict(17,"select");
			$this->assign("country_list",$country_list);
			
			
			
			$event_user_list=D("event_user")->event_user_select_pro(" and event_id=".$data["event_id"]." and event_user_parent_id=0  ");
			$this->assign("event_user_list",$event_user_list['item']);
			
			$event_info=M('event')->where(" event_id=".$data["event_id"]." ")->field("event_left_flag,event_right_flag,event_type")->find();
			$team_list[]=$event_info['event_left_flag'];
			$team_list[]=$event_info['event_right_flag'];
			
			$this->assign("event_info",$event_info);
			$this->assign("team_list",$team_list);
			$this->assign("page_title","修改赛事用户");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function event_user_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_user_id"]=post("event_user_id");
			$data["event_id"]=post("event_id");
			$data["field_uid"]=$_SESSION['field_uid'];
			$data["uid"]=post("uid");
			$data["event_user_realname"]=post("event_user_realname");
			$data["event_user_enrealname"]=post("event_user_enrealname");
			$data["event_user_sex"]=post("event_user_sex");
			$data["event_user_card_type"]=post("event_user_card_type");
			$data["event_user_card"]=post("event_user_card");
			$data["event_user_chadian"]=post("event_user_chadian");
			$data["event_user_state"]=post("event_user_state");
			
			$data["country"]=post("country");
			
			if(post("event_user_team")!="")
			{
				$data["event_user_team"]=post("event_user_team");
			}
			if(post("event_user_parent_id")!="")
			{
				$data["event_user_parent_id"]=post("event_user_parent_id");
			}

			$list=M("event_user")->save($data);
			$event_id=$data["event_id"];
			$event_user_id=$data["event_user_id"];
			$uid=$data["uid"];
			
			$upwhere='';
			if($data["event_user_enrealname"])
			{
				$event_user_enrealname=$data["event_user_enrealname"];
				$upwhere.=" ,enrealname='$event_user_enrealname' ";
			}
			if($data["event_user_realname"])
			{
				$event_user_realname=$data["event_user_realname"];
				$upwhere.=" ,realname='$event_user_realname' ";
			}
			if($data["country"])
			{
				$country=$data["country"];
				$upwhere.=" ,country='$country' ";
			}

			$res=M()->execute("update tbl_baofen set uid=$uid $upwhere where event_user_id=".$event_user_id." and event_id=".$event_id."  "); 

			$this->success("修改成功",U('admin/event_user/event_user',array('event_id'=>post('event_id'))));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/event_user/event_user',array('event_id'=>post('event_id'))));
		}

	}

	public function event_user_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("event_user")->where("event_user_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function event_user_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_event_user set event_user_state=1 where event_user_id=".$ids_arr[$i]." ");
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

	public function event_user_detail()
	{
		if(intval(get("event_user_id"))>0)
		{
			$data=M("event_user")->where("event_user_id=".intval(get("event_user_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["event_user_name"]."赛事用户");
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
	
	public function batch_apply()
	{
		$event_id=get('event_id');
		$fenzhan_list=D("fenzhan_tbl")->fenzhan_list_pro(" and event_id='".$event_id."' ");
		$this->assign('fenzhan_list',$fenzhan_list['item']);
		
		$this->display();
		
	}
	
	
	public function batch_apply_action()
	{
		if(post('event_id'))
		{
			$arr=explode(",",post('ids'));

			for($i=0; $i<count($arr); $i++)
			{
				//获取报名信息
				$event_user_id=$arr[$i];
				$user_info=M('event_user')->where(" event_user_id='".$event_user_id."' ")->find();
				if($arr[$i] && $user_info['event_user_id'])
				{
					
					$baoming_info=M()->query("select * from tbl_event_apply where event_user_id='".$event_user_id."' and fenzhan_id='".post('fenzhan_id')."' ");
					
					if(!$baoming_info[0]["event_apply_id"])
					{
						if($_SESSION['field_uid']==null)
						{
							$field_uid=0;
						}
						
						
						$data=array();
						$data['parent_id']=0;
						$data['event_id']=post('event_id');
						$data['fenzhan_id']=post('fenzhan_id');
						$data['field_uid']=$field_uid;
						$data['uid']=$user_info['uid'];
						$data['event_user_id']=$user_info['event_user_id'];
						$data['event_user_team']=$user_info['event_user_team'];
						$data['code_pic']=null;
						$data['event_apply_realname']=$user_info['event_user_realname'];
			            $data["event_apply_enrealname"]=post("event_user_enrealname");
						$data['event_apply_sex']=$user_info['event_user_sex'];
						$data['event_apply_card']=$user_info['event_user_card'];
						$data['event_apply_chadian']=$user_info['event_user_chadian'];
						$data['event_apply_state']=1;
						$data['event_apply_addtime']=time();
						$res=M('event_apply')->add($data);
					
					}
					
				}
			}
				
				
			//更新parent_id
			for($i=0; $i<count($arr); $i++)
			{
				//获取报名信息
				$event_user_id=$arr[$i];
				$user_info=M('event_user')->where(" event_user_id='".$event_user_id."' ")->find();
				if($arr[$i] && $user_info['event_user_id'])
				{
					
					if($user_info['event_user_parent_id']>0)
					{
						$baoming_info_parent=M()->query("select event_apply_id from tbl_event_apply where event_user_id='".$user_info['event_user_parent_id']."' and fenzhan_id='".post('fenzhan_id')."' ");
						
						$baoming_info=M()->query("select event_apply_id from tbl_event_apply where event_user_id='".$event_user_id."' and fenzhan_id='".post('fenzhan_id')."' ");
						
						if($baoming_info[0]["event_apply_id"])
						{
							$data=array();
							$data['event_apply_id']=$baoming_info[0]['event_apply_id'];
							$data['parent_id']=$baoming_info_parent[0]['event_apply_id'];
							$res=M('event_apply')->save($data);
						}
					}
					
				}
			}
	
			msg_dialog_tip('succeed^操作成功');
		}
		else
		{
			msg_dialog_tip('error^分组不能为空');
		}

	}


	

}
?>