<?php
/**
 *    #Case		bwvip
 *    #Page		Push_message_listAction.class.php (推送消息队列)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-07-02
 */
class push_message_listAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function push_message_list()
	{
		$list=D("push_message_list")->push_message_list_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","推送消息队列");
    	$this->display();
	}

	public function push_message_list_add()
	{
		
		$this->assign("page_title","添加推送消息队列");
    	$this->display();
	}

	public function push_message_list_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["message_id"]=post("message_id");
			$data["message_number"]=post("message_number");
			$data["message_type"]=post("message_type");
			$data["uid"]=post("uid");
			$data["message_title"]=post("message_title");
			$data["message_content"]=post("message_content");
			$data["devices_token"]=post("devices_token");
			$data["message_state"]=post("message_state");
			$data["receiver_type"]=post("receiver_type");
			$data["message_totalnum"]=post("message_totalnum");
			$data["message_sendnum"]=post("message_sendnum");
			$data["message_errorcode"]=post("message_errorcode");
			$data["message_errormsg"]=post("message_errormsg");
			$data["message_sendtime"]=strtotime(post("message_sendtime"));
			$data["message_addtime"]=time();
			
			$list=M("push_message_list")->add($data);
			$this->success("添加成功",U('admin/push_message_list/push_message_list'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/push_message_list/push_message_list_add'));
		}

	}


	public function push_message_list_edit()
	{
		if(intval(get("message_list_id"))>0)
		{
			$data=M("push_message_list")->where("message_list_id=".intval(get("message_list_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改推送消息队列");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function push_message_list_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["message_list_id"]=post("message_list_id");
			$data["message_id"]=post("message_id");
			$data["message_number"]=post("message_number");
			$data["message_type"]=post("message_type");
			$data["uid"]=post("uid");
			$data["message_title"]=post("message_title");
			$data["message_content"]=post("message_content");
			$data["devices_token"]=post("devices_token");
			$data["message_state"]=post("message_state");
			$data["receiver_type"]=post("receiver_type");
			$data["message_totalnum"]=post("message_totalnum");
			$data["message_sendnum"]=post("message_sendnum");
			$data["message_errorcode"]=post("message_errorcode");
			$data["message_errormsg"]=post("message_errormsg");
			$data["message_sendtime"]=strtotime(post("message_sendtime"));
			
			$list=M("push_message_list")->save($data);
			$this->success("修改成功",U('admin/push_message_list/push_message_list'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/push_message_list/push_message_list'));
		}

	}

	public function push_message_list_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("push_message_list")->where("message_list_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function push_message_list_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_push_message_list set push_message_list_state=1 where message_list_id=".$ids_arr[$i]." ");
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

	public function push_message_list_detail()
	{
		if(intval(get("message_list_id"))>0)
		{
			$data=M("push_message_list")->where("message_list_id=".intval(get("message_list_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["push_message_list_name"]."推送消息队列");
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