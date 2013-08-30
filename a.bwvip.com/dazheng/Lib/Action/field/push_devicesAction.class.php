<?php
/**
 *    #Case		bwvip
 *    #Page		Push_devicesAction.class.php (推送设备)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-07-02
 */
class push_devicesAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function push_devices()
	{
		$list=D("push_devices")->push_devices_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","推送设备");
    	$this->display();
	}

	public function push_devices_add()
	{
		
		$this->assign("page_title","添加推送设备");
    	$this->display();
	}

	public function push_devices_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["devices_name"]=post("devices_name");
			$data["devices_version"]=post("devices_version");
			$data["devices_token"]=post("devices_token");
			$data["devices_type"]=post("devices_type");
			$data["mode"]=post("mode");
			$data["badge_number"]=post("badge_number");
			$data["status"]=post("status");
			$data["addtime"]=time();
			
			$list=M("push_devices")->add($data);
			$this->success("添加成功",U('field/push_devices/push_devices'));
		}
		else
		{
			$this->error("不能重复提交",U('field/push_devices/push_devices_add'));
		}

	}


	public function push_devices_edit()
	{
		if(intval(get("id"))>0)
		{
			$data=M("push_devices")->where("id=".intval(get("id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改推送设备");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function push_devices_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["id"]=post("id");
			$data["uid"]=post("uid");
			$data["devices_name"]=post("devices_name");
			$data["devices_version"]=post("devices_version");
			$data["devices_token"]=post("devices_token");
			$data["devices_type"]=post("devices_type");
			$data["mode"]=post("mode");
			$data["badge_number"]=post("badge_number");
			$data["status"]=post("status");
			
			$list=M("push_devices")->save($data);
			$this->success("修改成功",U('field/push_devices/push_devices'));			
		}
		else
		{
			$this->error("不能重复提交",U('field/push_devices/push_devices'));
		}

	}

	public function push_devices_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("push_devices")->where("id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function push_devices_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_push_devices set push_devices_state=1 where id=".$ids_arr[$i]." ");
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

	public function push_devices_detail()
	{
		if(intval(get("id"))>0)
		{
			$data=M("push_devices")->where("id=".intval(get("id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["push_devices_name"]."推送设备");
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