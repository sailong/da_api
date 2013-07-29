<?php
/**
 *    #Case		bwvip
 *    #Page		FenzhanAction.class.php (分站)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class fenzhanAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function fenzhan()
	{
		$list=D("fenzhan")->fenzhan_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","分站");
    	$this->display();
	}

	public function fenzhan_add()
	{

		$this->assign("page_title","添加分站");
    	$this->display();
	}

	public function fenzhan_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["sid"]=post("sid");
			$data["fenz_name"]=post("fenz_name");
			$data["field_id"]=post("field_id");
			$data["year"]=post("year");
			if($_FILES["timepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/fenzhan/");
				$data["timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			if(post("starttime"))
			{
				$data["starttime"]=strtotime(post("starttime")." 00:00:01");
			}
			if(post("endtime"))
			{
				$data["endtime"]=strtotime(post("endtime")." 23:59:59");
			}
			
			$data["orderby"]=post("orderby");
			$data["is_delete"]=post("is_delete");
			$data["addtime"]=time();
			
			$list=M("fenzhan","pre_")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/fenzhan/fenzhan'));
			}
			else
			{				
				$this->error("添加失败",U('admin/fenzhan/fenzhan_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzhan/fenzhan_add'));
		}

	}


	public function fenzhan_edit()
	{
		if(intval(get("fz_id"))>0)
		{
			$data=M("fenzhan","pre_")->where("fz_id=".intval(get("fz_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改分站");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function fenzhan_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["fz_id"]=post("fz_id");
			$data["sid"]=post("sid");
			$data["fenz_name"]=post("fenz_name");
			$data["field_id"]=post("field_id");
			$data["year"]=post("year");
			if($_FILES["timepic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/fenzhan/");
				$data["timepic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			if(post("starttime"))
			{
				$data["starttime"]=strtotime(post("starttime")." 00:00:01");
			}
			if(post("endtime"))
			{
				$data["endtime"]=strtotime(post("endtime")." 23:59:59");
			}
			$data["orderby"]=post("orderby");
			$data["is_delete"]=post("is_delete");
			
			$list=M("fenzhan","pre_")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/fenzhan/fenzhan'));
			}
			else
			{				
				$this->error("修改失败",U('admin/fenzhan/fenzhan'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/fenzhan/fenzhan'));
		}

	}

	public function fenzhan_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("fenzhan","pre_")->where("fz_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function fenzhan_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_fenzhan set fenzhan_state=1 where fz_id=".$ids_arr[$i]." ");
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

	public function fenzhan_detail()
	{
		if(intval(get("fz_id"))>0)
		{
			$data=M("fenzhan","pre_")->where("fz_id=".intval(get("fz_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["fenzhan_name"]."分站");
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