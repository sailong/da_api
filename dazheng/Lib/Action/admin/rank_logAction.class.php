<?php
/**
 *    #Case		bwvip
 *    #Page		Rank_logAction.class.php (排名记录)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2014-03-05
 */
class rank_logAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function rank_log()
	{
		$list=D("rank_log")->rank_log_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","排名记录");
    	$this->display();
	}

	public function rank_log_add()
	{
		
		$this->assign("page_title","添加排名记录");
    	$this->display();
	}

	public function rank_log_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["event_level"]=post("event_level");
			$data["user_group"]=post("user_group");
			$data["rank_num"]=post("rank_num");
			$data["rank_total_score"]=post("rank_total_score");
			$data["rank_score"]=post("rank_score");
			$data["rank_reward_score"]=post("rank_reward_score");
			$data["rank_log_addtime"]=time();
			
			$list=M("rank_log")->add($data);
			$this->success("添加成功",U('admin/rank_log/rank_log'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank_log/rank_log_add'));
		}

	}


	public function rank_log_edit()
	{
		if(intval(get("rank_log_id"))>0)
		{
			$data=M("rank_log")->where("rank_log_id=".intval(get("rank_log_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改排名记录");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function rank_log_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rank_log_id"]=post("rank_log_id");
			$data["uid"]=post("uid");
			$data["event_id"]=post("event_id");
			$data["event_level"]=post("event_level");
			$data["user_group"]=post("user_group");
			$data["rank_num"]=post("rank_num");
			$data["rank_total_score"]=post("rank_total_score");
			$data["rank_score"]=post("rank_score");
			$data["rank_reward_score"]=post("rank_reward_score");
			
			$list=M("rank_log")->save($data);
			$this->success("修改成功",U('admin/rank_log/rank_log'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/rank_log/rank_log'));
		}

	}

	public function rank_log_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("rank_log")->where("rank_log_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function rank_log_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_rank_log set rank_log_state=1 where rank_log_id=".$ids_arr[$i]." ");
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

	public function rank_log_detail()
	{
		if(intval(get("rank_log_id"))>0)
		{
			$data=M("rank_log")->where("rank_log_id=".intval(get("rank_log_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["rank_log_name"]."排名记录");
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