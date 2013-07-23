<?php
/**
 *    #Case		kalatai
 *    #Page		Dict_typeAction.class.php (字典分类)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class dict_typeAction extends AdminAuthAction
{

	public function _initialize()	
	{
		parent::_initialize();
	}

	public function dict_type()
	{
		$list=D("dict_type")->dict_type_list_pro();
		

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","字典分类");
    	$this->display();
	}

	public function dict_type_add()
	{

		$this->assign("page_title","添加字典分类");
    	$this->display();
	}

	public function dict_type_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["dict_type_name"]=post("dict_type_name");
			$data["dict_type_iskey"]=post("dict_type_iskey");
			
			$list=M("dict_type")->add($data);
			if($list!=false)
			{
				$this->success("添加成功");
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


	public function dict_type_edit()
	{
		if(intval(get("dict_type_id"))>0)
		{
			$data=M("dict_type")->where("dict_type_id=".intval(get("dict_type_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改字典分类");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function dict_type_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["dict_type_id"]=post("dict_type_id");
			$data["dict_type_name"]=post("dict_type_name");
			$data["dict_type_iskey"]=post("dict_type_iskey");
			
			$list=M("dict_type")->save($data);
			if($list!=false)
			{
				$this->success("修改成功");
			}
			else
			{
				$this->error("修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function dict_type_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("dict_type")->where("dict_type_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function dict_type_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_dict_type set dict_type_state=1 where dict_type_id=".$ids_arr[$i]." ");
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

	public function dict_type_detail()
	{
		if(intval(get("dict_type_id"))>0)
		{
			$data=M("dict_type")->where("dict_type_id=".intval(get("dict_type_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["dict_type_name"]."字典分类");
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