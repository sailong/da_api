<?php
/**
 *    #Case		tankuang
 *    #Page		AdAction.class.php (广告)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
 
class field_picAction extends AdminAuthAction
{

	public function _basic()
	{
		parent::_basic();
	}

	public function field_pic()
	{
	
		
		$list=D("field_pic")->field_pic_list_pro();
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场客户端图片");
    	$this->display();
	}

	public function field_pic_add()
	{
		/* $page_list=select_dict(3,"select");
		$this->assign("page_list",$page_list);
		$action_list=select_dict(16,"select");
		$this->assign("action_list",$action_list);
		
		$event=D('event')->event_select_pro("  ");
		$this->assign('event',$event['item']); */
		
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);

		$this->assign("page_title","添加广告");
    	$this->display();
	}

	public function field_pic_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_pic_name"]=post("field_pic_name");
			
		
			if($_FILES["field_pic_file"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/field_pic/");
				
				$data["field_pic_url"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			
			}
			$data["field_pic_sort"]=post("field_pic_sort");
			
			if(post("field_uid"))
			{
				$data["field_uid"]=post("field_uid");
			}
			
			$data["field_pic_addtime"]=time();
			
			$list=M("field_pic")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/field_pic/field_pic'));
				//msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				$this->success("添加失败",U('admin/field_pic/field_pic'));
				//msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function field_pic_edit()
	{
		if(intval(get("field_pic_id"))>0)
		{
			$data=M("field_pic")->where("field_pic_id=".intval(get("field_pic_id")))->find();
			$this->assign("data",$data);
			
			$app_list=select_field(1,"select");
			$this->assign("app_list",$app_list);
			
			$this->assign("page_title","修改图片");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_pic_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_pic_name"]=post("field_pic_name");
			$data["field_pic_id"]=post("field_pic_id");
		
			if($_FILES["field_pic_file"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/field_pic/");
				$data["field_pic_url"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["field_pic_sort"]=post("field_pic_sort");
			
			if(post("field_uid"))
			{
				$data["field_uid"]=post("field_uid");
			}
			$list=M("field_pic")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/field_pic/field_pic'));
				//msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				$this->success("修改失败",U('admin/field_pic/field_pic'));
				//msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function field_pic_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field_pic")->where("field_pic_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_pic_detail()
	{
		if(intval(get("field_pic_id"))>0)
		{
			$data=M("field_pic")->where("field_pic_id=".intval(get("field_pic_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["field_pic_name"]);
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