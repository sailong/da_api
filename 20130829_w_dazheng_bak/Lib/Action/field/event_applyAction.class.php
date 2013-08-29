<?php
/**
 *    #Case		bwvip
 *    #Page		Event_applyAction.class.php (赛事报名)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class event_applyAction extends field_publicAction
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
		
		$list=D("event_apply")->event_apply_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

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
			
			$list=M("event_apply")->add($data);
		
			$this->success("添加成功",U('field/event_apply/event_apply',array('event_id'=>$data['event_id'])));
			
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
			$event=D('event')->event_select_pro(" and field_uid='".$_SESSION['field_uid']."' ");
			$this->assign('event',$event['item']);
			
			$fenzhan=D('fenzhan_tbl')->fenzhan_list_pro(" and field_uid='".$_SESSION['field_uid']."'  ");
			$this->assign('fenzhan',$fenzhan['item']);
			
			$data=M("event_apply")->where("event_apply_id=".intval(get("event_apply_id")))->find();
			$this->assign("data",$data);
			
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
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			
			$list=M("event_apply")->save($data);
			$this->success("修改成功",U('field/event_apply/event_apply',array('event_id'=>$data['event_id'])));
		
		}
		else
		{
			$this->error("参数错误或来路非法","/");
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


	

}
?>