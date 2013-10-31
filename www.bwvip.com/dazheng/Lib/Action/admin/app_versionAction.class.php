<?php
/**
 *    #Case		bwvip
 *    #Page		App_versionAction.class.php (客户端版本)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class app_versionAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function app_version()
	{
		$list=D("app_version")->app_version_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","客户端版本");
    	$this->display();
	}

	public function app_version_add()
	{
		$page_list=select_field(1,"select");
		$this->assign("page_list",$page_list);

		$this->assign("page_title","添加客户端版本");
    	$this->display();
	}

	public function app_version_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["app_version_type"]=post("app_version_type");
			$data["app_version_number"]=post("app_version_number");
			$data["app_version_name"]=post("app_version_name");
			$data["app_version_content"]=post("app_version_content");
			$data["app_version_file"]=post("app_version_file");
			$data["app_version_url"]=post("app_version_url");
			$data["app_version_is_important"]=post("app_version_is_important");
			$data["field_uid"]=post("field_uid");
			$data["app_version_addtime"]=time();
			
			$list=M("app_version")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/app_version/app_version'));
			}
			else
			{				
				$this->error("添加失败",U('admin/app_version/app_version'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function app_version_edit()
	{
		if(intval(get("app_version_id"))>0)
		{
			$data=M("app_version")->where("app_version_id=".intval(get("app_version_id")))->find();
			$this->assign("data",$data);
			
			$page_list=select_field(1,"select");
			$this->assign("page_list",$page_list);
			
			$this->assign("page_title","修改客户端版本");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function app_version_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["app_version_id"]=post("app_version_id");
			$data["app_version_type"]=post("app_version_type");
			$data["app_version_number"]=post("app_version_number");
			$data["app_version_name"]=post("app_version_name");
			$data["app_version_content"]=post("app_version_content");
			$data["app_version_file"]=post("app_version_file");
			$data["app_version_url"]=post("app_version_url");
			$data["field_uid"]=post("field_uid");
			if(post("app_version_is_important"))
			{
				$data["app_version_is_important"]=post("app_version_is_important");
			}
			
			$list=M("app_version")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/app_version/app_version'));
			}
			else
			{
				$this->error("修改失败",U('admin/app_version/app_version'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function app_version_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("app_version")->where("app_version_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function app_version_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_app_version set app_version_state=1 where app_version_id=".$ids_arr[$i]." ");
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

	public function app_version_detail()
	{
		if(intval(get("app_version_id"))>0)
		{
			$data=M("app_version")->where("app_version_id=".intval(get("app_version_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["app_version_name"]."客户端版本");
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