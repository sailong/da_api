<?php
/**
 *    #Case		bwvip
 *    #Page		Event_applyAction.class.php (赛事报名)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class event_applyAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function event_apply()
	{
		$list=D("event_apply")->event_apply_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","赛事报名");
    	$this->display();
	}

	public function event_apply_add()
	{

		$this->assign("page_title","添加赛事报名");
    	$this->display();
	}

	public function event_apply_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["event_id"]=post("event_id");
			$data["uid"]=post("uid");
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			$data["event_apply_addtime"]=time();
			
			$list=M("event_apply")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/event_apply/event_apply'));
			}
			else
			{				
				$this->error("添加失败",U('admin/event_apply/event_apply'));
			}
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
			$data["uid"]=post("uid");
			$data["event_apply_realname"]=post("event_apply_realname");
			$data["event_apply_sex"]=post("event_apply_sex");
			$data["event_apply_card"]=post("event_apply_card");
			$data["event_apply_chadian"]=post("event_apply_chadian");
			$data["event_apply_state"]=post("event_apply_state");
			
			$list=M("event_apply")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/event_apply/event_apply'));
			}
			else
			{
				$this->error("修改失败",U('admin/event_apply/event_apply'));
			}
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
				echo "error^审核成功";
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