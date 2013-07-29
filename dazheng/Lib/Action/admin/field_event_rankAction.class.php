<?php
/**
 *    #Case		bwvip
 *    #Page		Field_event_rankAction.class.php (球场比赛排名)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class field_event_rankAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function field_event_rank()
	{
		$list=D("field_event_rank")->field_event_rank_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场比赛排名");
    	$this->display();
	}

	public function field_event_rank_add()
	{

		$this->assign("page_title","添加球场比赛排名");
    	$this->display();
	}

	public function field_event_rank_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_event_rank_name"]=post("field_event_rank_name");
			$data["field_event_rank_name_en"]=post("field_event_rank_name_en");
			$data["uid"]=post("uid");
			$data["field_uid"]=post("field_uid");
			$data["field_event_rank_score"]=post("field_event_rank_score");
			$data["field_event_rank_sort"]=post("field_event_rank_sort");
			$data["field_event_rank_addtime"]=time();
			
			$list=M("field_event_rank")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/field_event_rank/field_event_rank'));
			}
			else
			{				
				$this->error("添加失败",U('admin/field_event_rank/field_event_rank_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_event_rank/field_event_rank_add'));
		}

	}


	public function field_event_rank_edit()
	{
		if(intval(get("field_event_rank_id"))>0)
		{
			$data=M("field_event_rank")->where("field_event_rank_id=".intval(get("field_event_rank_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改球场比赛排名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_event_rank_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_event_rank_id"]=post("field_event_rank_id");
			$data["field_event_rank_name"]=post("field_event_rank_name");
			$data["field_event_rank_name_en"]=post("field_event_rank_name_en");
			$data["uid"]=post("uid");
			$data["field_uid"]=post("field_uid");
			$data["field_event_rank_score"]=post("field_event_rank_score");
			$data["field_event_rank_sort"]=post("field_event_rank_sort");
			
			$list=M("field_event_rank")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/field_event_rank/field_event_rank'));
			}
			else
			{				
				$this->error("修改失败",U('admin/field_event_rank/field_event_rank'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_event_rank/field_event_rank'));
		}

	}

	public function field_event_rank_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field_event_rank")->where("field_event_rank_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_event_rank_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_field_event_rank set field_event_rank_state=1 where field_event_rank_id=".$ids_arr[$i]." ");
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

	public function field_event_rank_detail()
	{
		if(intval(get("field_event_rank_id"))>0)
		{
			$data=M("field_event_rank")->where("field_event_rank_id=".intval(get("field_event_rank_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["field_event_rank_name"]."球场比赛排名");
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