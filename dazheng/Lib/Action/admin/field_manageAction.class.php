<?php
/**
 *    #Case		tankuang
 *    #Page		UserAction.class.php (用户)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class field_manageAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function field_list()
	{
		$list=D("field_manage")->field_list_pro();
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);

		$this->assign("page_title","客户端");
    	$this->display();
	}

	

	public function field_add()
	{

		$this->assign("page_title","添加客户端");
    	$this->display();
	}

	public function field_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["field_name"]	= post('field_name');
			$data["field_status"] = post('field_status');
			$data["field_content"]=stripslashes(post("field_content"));
			$data["field_download_num"]=post("field_download_num");
			$data["field_zuobiao"]=post("field_zuobiao");
			$data["field_addtime"]=time();
			$uploadinfo=upload_file("upload/field_pic/");
			if($_FILES["field_pic"]["error"]==0 && $_FILES["field_pic"]["name"])
			{
				$data["field_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			
			
			$list=M("field")->add($data); 
			
			if($list!=false)
			{
					$this->success("添加成功",U('admin/field_manage/field_list',array()));
			}
			else
			{
				$this->error("添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function field_edit()
	{
		if(intval(get("field_id"))>0)
		{
			$data=M("field")->where("field_id=".intval(get("field_id")))->find();
			
			$this->assign("data",$data); 
			 
			
			$this->assign("page_title","修改客户端");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$field_id=post("field_id");
			$data["field_uid"]=post("field_uid");
			$data["field_name"]	= post('field_name');
			$data["field_status"] = post('field_status');
			$data["field_content"]=stripslashes(post("field_content"));
			$data["field_download_num"]=post("field_download_num");
			$data["field_zuobiao"]=post("field_zuobiao");
			$data["field_addtime"]=time();
			$uploadinfo=upload_file("upload/field_pic/");
			if($_FILES["field_pic"]["error"]==0 && $_FILES["field_pic"]["name"])
			{
				$data["field_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			
			$list=M("field")->where("field_id='{$field_id}'")->save($data);
			
			if($list!=false)
			{ 
				
					$this->success("修改成功",U('admin/field_manage/field_list',array()));
			}
			else
			{ 
					$this->success("修改失败",U('admin/field_manage/field_list',array()));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function field_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field")->where("field_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}
	
	public function field_detail()
	{
		if(intval(get("uid"))>0)
		{
			$data=M("common_member","pre_")->where("uid=".intval(get("uid")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["username"]."用户");
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