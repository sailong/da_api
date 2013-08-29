<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticket_logAction.class.php (验票)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticket_logAction extends piao_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
	}

	public function user_ticket_log()
	{
		$list=D("user_ticket_log")->user_ticket_log_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","验票");
    	$this->display();
	}

	public function user_ticket_log_add()
	{
		
		$this->assign("page_title","添加验票");
    	$this->display();
	}

	public function user_ticket_log_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["ticket_id"]=post("ticket_id");
			$data["user_ticket_code"]=post("user_ticket_code");
			$data["user_ticket_log_source"]=post("user_ticket_log_source");
			$data["user_ticket_log_status"]=post("user_ticket_log_status");
			$data["user_ticket_log_addtime"]=time();
			
			$list=M("user_ticket_log")->add($data);
			$this->success("添加成功",U('piao/user_ticket_log/user_ticket_log'));
		}
		else
		{
			$this->error("不能重复提交",U('piao/user_ticket_log/user_ticket_log_add'));
		}

	}


	public function user_ticket_log_edit()
	{
		if(intval(get("user_ticket_log_id"))>0)
		{
			$data=M("user_ticket_log")->where("user_ticket_log_id=".intval(get("user_ticket_log_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改验票");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function user_ticket_log_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["user_ticket_log_id"]=post("user_ticket_log_id");
			$data["uid"]=post("uid");
			$data["ticket_id"]=post("ticket_id");
			$data["user_ticket_code"]=post("user_ticket_code");
			$data["user_ticket_log_source"]=post("user_ticket_log_source");
			$data["user_ticket_log_status"]=post("user_ticket_log_status");
			
			$list=M("user_ticket_log")->save($data);
			$this->success("修改成功",U('piao/user_ticket_log/user_ticket_log'));			
		}
		else
		{
			$this->error("不能重复提交",U('piao/user_ticket_log/user_ticket_log'));
		}

	}

	public function user_ticket_log_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("user_ticket_log")->where("user_ticket_log_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function user_ticket_log_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_user_ticket_log set user_ticket_log_state=1 where user_ticket_log_id=".$ids_arr[$i]." ");
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

	public function user_ticket_log_detail()
	{
		if(intval(get("user_ticket_log_id"))>0)
		{
			$data=M("user_ticket_log")->where("user_ticket_log_id=".intval(get("user_ticket_log_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["user_ticket_log_name"]."验票");
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