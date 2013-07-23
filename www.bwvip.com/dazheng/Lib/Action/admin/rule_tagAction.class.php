<?php
/**
 *    #Case		bwvip
 *    #Page		Rule_tagAction.class.php (规则江湖)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class rule_tagAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function rule_tag()
	{
		$list=D("rule_tag")->rule_tag_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","规则江湖");
    	$this->display();
	}

	public function rule_tag_add()
	{

		$this->assign("page_title","添加规则江湖");
    	$this->display();
	}

	public function rule_tag_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rule_tag_name"]=post("rule_tag_name");
			if($_FILES["rule_tag_logo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/rule_tag/");
				$data["rule_tag_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["rule_tag_content"]=post("rule_tag_content");
			$data["rule_tag_addtime"]=time();
			
			$list=M("rule_tag")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/rule_tag/rule_tag'));
			}
			else
			{				
				$this->error("添加失败",U('admin/rule_tag/rule_tag'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function rule_tag_edit()
	{
		if(intval(get("rule_tag_id"))>0)
		{
			$data=M("rule_tag")->where("rule_tag_id=".intval(get("rule_tag_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改规则江湖");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function rule_tag_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["rule_tag_id"]=post("rule_tag_id");
			$data["rule_tag_name"]=post("rule_tag_name");
			if($_FILES["rule_tag_logo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/rule_tag/");
				$data["rule_tag_logo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["rule_tag_content"]=post("rule_tag_content");
			
			$list=M("rule_tag")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/rule_tag/rule_tag'));
			}
			else
			{
				$this->error("修改失败",U('admin/rule_tag/rule_tag'));
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function rule_tag_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("rule_tag")->where("rule_tag_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function rule_tag_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_rule_tag set rule_tag_state=1 where rule_tag_id=".$ids_arr[$i]." ");
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

	public function rule_tag_detail()
	{
		if(intval(get("rule_tag_id"))>0)
		{
			$data=M("rule_tag")->where("rule_tag_id=".intval(get("rule_tag_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["rule_tag_name"]."规则江湖");
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