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
		$list=D("event_user")->event_user_list_pro($sql,150," event_user_addtime desc ");
$event_info=M("event")->where("event_id=".intval(get("event_id")))->find();
		$this->assign('event_name',$event_info['event_name']);
		$this->assign('event_id',$event_info['event_id']);
		

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
			$data["event_user_sex"]=post("event_user_sex");
			$data["event_user_card_type"]=post("event_user_card_type");
			$data["event_user_card"]=post("event_user_card");
			$data["event_user_chadian"]=post("event_user_chadian");
			$data["event_user_state"]=1;
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
			$data["event_user_sex"]=post("event_user_sex");
			$data["event_user_card_type"]=post("event_user_card_type");
			$data["event_user_card"]=post("event_user_card");
			$data["event_user_chadian"]=post("event_user_chadian");
			$data["event_user_state"]=post("event_user_state");
			
			$list=M("event_user")->save($data);
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
						$data['parent_id']=0;
						$data['event_id']=post('event_id');
						$data['fenzhan_id']=post('fenzhan_id');
						$data['field_uid']=$_SESSION['field_uid'];
						$data['uid']=$user_info['uid'];
						$data['event_user_id']=$user_info['event_user_id'];
						$data['event_apply_realname']=$user_info['event_user_realname'];
						$data['event_apply_sex']=$user_info['event_user_sex'];
						$data['event_apply_card']=$user_info['event_user_card'];
						$data['event_apply_chadian']=$user_info['event_user_chadian'];
						$data['event_apply_state']=1;
						$data['event_apply_addtime']=time();
						
						$res=M('event_apply')->add($data);
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