<?php
/**
 *    #Case		tankuang
 *    #Page		AdAction.class.php (广告)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
 
class msg_logAction extends AdminAuthAction
{

	public function _basic()
	{
		parent::_basic();
	}

	public function msg_log()
	{
		$list=D("msg_log")->msg_log_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$this->assign("page_title","短信");
    	$this->display();
	}

	public function msg_log_add()
	{
	
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);

		$this->assign("page_title","添加短信");
    	$this->display();
	}

	public function msg_log_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["mobile"]=post("mobile");
			$data["code"]=post("code");
			$data["msg_log_source"]=post("msg_log_source");
			$data["content"]=post("content");
			$data["msg_log_status"]=post("msg_log_status");
			$data["msg_log_addtime"]=time();
			//$data["msg_log_sendtime"]=post("msg_log_sendtime");
			//$data["msg_log_date"]=post("ad_action_text");
			
			
			$list=M("msg_log")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/msg_log/msg_log'));
				//msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				$this->success("添加失败",U('admin/msg_log/msg_log'));
				//msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function msg_log_edit()
	{
		if(intval(get("msg_log_id"))>0)
		{
			$data=M("msg_log")->where("msg_log_id=".intval(get("msg_log_id")))->find();
			$this->assign("data",$data);
			
			$app_list=select_field(1,"select");
			$this->assign("app_list",$app_list);
			
			$this->assign("page_title","修改短信");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function msg_log_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["msg_log_id"]=post("msg_log_id");
			
			$data["field_uid"]=post("field_uid");
			$data["mobile"]=post("mobile");
			$data["code"]=post("code");
			$data["msg_log_source"]=post("msg_log_source");
			$data["content"]=post("content");
			$data["msg_log_status"]=post("msg_log_status");
			$data["msg_log_addtime"]=time();
			
			$list=M("msg_log")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/msg_log/msg_log'));
				//msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				$this->success("修改失败",U('admin/msg_log/msg_log'));
				//msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function msg_log_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("msg_log")->where("msg_log_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	

	public function msg_log_detail()
	{
		if(intval(get("ad_id"))>0)
		{
			$data=M("msg_log")->where("msg_log_id=".intval(get("msg_log_id")))->find();
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