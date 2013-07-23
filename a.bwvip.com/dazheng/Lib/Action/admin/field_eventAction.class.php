<?php
/**
 *    #Case		bwvip
 *    #Page		Field_eventAction.class.php (球场比赛)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class field_eventAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function field_event()
	{
		$list=D("field_event")->field_event_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场比赛");
    	$this->display();
	}

	public function field_event_add()
	{

		$this->assign("page_title","添加球场比赛");
    	$this->display();
	}

	public function field_event_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_event_name"]=post("field_event_name");
			$data["field_event_name_en"]=post("field_event_name_en");
			if($_FILES["field_event_logo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/field_event/");
				$data["field_event_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["field_uid"]=post("field_uid");
			$data["field_event_time"]=strtotime(post("field_event_time"));
			$data["field_event_addtime"]=time();
			
			$list=M("field_event")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/field_event/field_event'));
			}
			else
			{				
				$this->error("添加失败",U('admin/field_event/field_event_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_event/field_event_add'));
		}

	}


	public function field_event_edit()
	{
		if(intval(get("field_event_id"))>0)
		{
			$data=M("field_event")->where("field_event_id=".intval(get("field_event_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改球场比赛");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_event_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_event_id"]=post("field_event_id");
			$data["field_event_name"]=post("field_event_name");
			$data["field_event_name_en"]=post("field_event_name_en");
			if($_FILES["field_event_logo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/field_event/");
				$data["field_event_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["field_uid"]=post("field_uid");
			$data["field_event_time"]=strtotime(post("field_event_time"));
			
			$list=M("field_event")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/field_event/field_event'));
			}
			else
			{				
				$this->error("修改失败",U('admin/field_event/field_event'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_event/field_event'));
		}

	}

	public function field_event_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field_event")->where("field_event_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_event_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_field_event set field_event_state=1 where field_event_id=".$ids_arr[$i]." ");
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

	public function field_event_detail()
	{
		if(intval(get("field_event_id"))>0)
		{
			$data=M("field_event")->where("field_event_id=".intval(get("field_event_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["field_event_name"]."球场比赛");
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