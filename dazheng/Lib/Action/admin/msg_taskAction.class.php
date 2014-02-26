<?php
/**
 *    #Case		tankuang
 *    #Page		AdAction.class.php (广告)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
 
class msg_taskAction extends AdminAuthAction
{

	public function _basic()
	{
		parent::_basic();
	}

	public function msg_task()
	{
		$list=D("msg_task")->msg_task_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign("page_title","短信任务");
    	$this->display();
	}

	public function msg_task_add()
	{
	
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);

		$this->assign("page_title","添加短信");
    	$this->display();
	}

	public function msg_task_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["mobile"]=post("mobile");
			$data["code"]=post("code");
			$data["msg_task_source"]=post("msg_task_source");
			$data["msg_task_success_num"]=post("msg_task_success_num");
			$data["msg_task_err_num"]=post("msg_task_err_num");
			$data["msg_task_addtime"]=time();
			$data["msg_task_status"]=post("msg_task_status");
			//$data["msg_log_date"]=post("ad_action_text");
			
			
			$list=M("msg_task")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/msg_task/msg_task'));
				//msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				$this->success("添加失败",U('admin/msg_task/msg_task'));
				//msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function msg_task_edit()
	{
		if(intval(get("msg_task_id"))>0)
		{
			$data=M("msg_task")->where("msg_task_id=".intval(get("msg_task_id")))->find();
			$this->assign("data",$data);
			
			$app_list=select_field(1,"select");
			$this->assign("app_list",$app_list);
			
			$this->assign("page_title","修改短信任务");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function msg_task_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["msg_task_id"]=post("msg_task_id");
			
			$data["field_uid"]=post("field_uid");
			$data["mobile"]=post("mobile");
			$data["code"]=post("code");
			$data["msg_task_source"]=post("msg_task_source");
			$data["msg_task_success_num"]=post("msg_task_success_num");
			$data["msg_task_err_num"]=post("msg_task_err_num");
			//$data["msg_task_addtime"]=time();
			$data["msg_task_status"]=post("msg_task_status");
			
			$list=M("msg_task")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/msg_task/msg_task'));
				//msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				$this->success("修改失败",U('admin/msg_task/msg_task'));
				//msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function msg_task_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("msg_task")->where("msg_task_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	

	public function msg_task_detail()
	{
		if(intval(get("msg_task_id"))>0)
		{
			$data=M("msg_task")->where("msg_task_id=".intval(get("msg_task_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				//$this->assign("page_title",$data["ad_name"]."广告");
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