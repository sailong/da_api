<?php
/**
 *    #Case		bwvip
 *    #Page		RankAction.class.php (排名)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2014-03-05
 */
class rankAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function rank()
	{
		$list=D("rank")->rank_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","排名");
    	$this->display();
	}

	public function rank_add()
	{
		
		$this->assign("page_title","添加排名");
    	$this->display();
	}

	public function rank_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["user_group"]=post("user_group");
			$data["rank_type"]=post("rank_type");
			$data["rank_total_score"]=post("rank_total_score");
			$data["rank_score"]=post("rank_score");
			$data["rank_change"]=post("rank_change");
			$data["rank_last"]=post("rank_last");
			$data["rank_addtime"]=time();
			
			$list=M("rank")->add($data);
			$this->success("添加成功",U('admin/rank/rank'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank/rank_add'));
		}

	}


	public function rank_edit()
	{
		if(intval(get("rank_id"))>0)
		{
			$data=M("rank")->where("rank_id=".intval(get("rank_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改排名");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function rank_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rank_id"]=post("rank_id");
			$data["uid"]=post("uid");
			$data["user_group"]=post("user_group");
			$data["rank_type"]=post("rank_type");
			$data["rank_total_score"]=post("rank_total_score");
			$data["rank_score"]=post("rank_score");
			$data["rank_change"]=post("rank_change");
			$data["rank_last"]=post("rank_last");
			
			$list=M("rank")->save($data);
			$this->success("修改成功",U('admin/rank/rank'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank/rank'));
		}

	}

	public function rank_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("rank")->where("rank_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function rank_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_rank set rank_state=1 where rank_id=".$ids_arr[$i]." ");
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

	public function rank_detail()
	{
		if(intval(get("rank_id"))>0)
		{
			$data=M("rank")->where("rank_id=".intval(get("rank_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["rank_name"]."排名");
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