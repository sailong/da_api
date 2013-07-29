<?php
/**
 *    #Case		tankuang
 *    #Page		ArctypeAction.class.php (栏目)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class arctypeAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function arctype()
	{
		$list=D("arctype")->arctype_admin_list_pro(" and arctype_parent_id=0 ");

		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","栏目");
    	$this->display();
	}

	public function arctype_add()
	{
		$arctype_list=D("arctype")->arctype_select_pro(" and arctype_parent_id=0 ");
		$this->assign("arctype_list",$arctype_list['item']);

		$arctype_type=select_dict(2,"select");
		$this->assign("arctype_type",$arctype_type);

		import("@.ORG.editor");  //导入类
		$editor=new editor("450px","800px",$data['arctype_content'],"arctype_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);


		$this->assign("page_title","添加栏目");
    	$this->display();
	}

	public function arctype_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arctype_name"]=post("arctype_name");
			$data["arctype_parent_id"]=post("arctype_parent_id");
			$data["arctype_is_parent"]=post("arctype_is_parent");
			$data["arctype_depth"]=post("arctype_depth");
			$data["arctype_url"]=post("arctype_url");
			$data["arctype_path"]=post("arctype_path");
			$data["arctype_sort"]=post("arctype_sort");
			$data["arctype_type"]=post("arctype_type");
			$data["arctype_content"]=stripslashes($_POST["arctype_content"]);
			$data["arctype_state"]=1;
			
			$list=M("arctype")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/arctype/arctype'));
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


	public function arctype_edit()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			$this->assign("data",$data);

			import("@.ORG.editor");  //导入类
			$editor=new editor("450px","800px",$data['arctype_content'],"arctype_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);

			$arctype_list=D("arctype")->arctype_select_pro(" and arctype_parent_id=0 ");
			$this->assign("arctype_list",$arctype_list['item']);

			$arctype_type=select_dict(2,"select");
			$this->assign("arctype_type",$arctype_type);

			
			$this->assign("page_title","修改栏目");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function arctype_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arctype_id"]=post("arctype_id");
			$data["arctype_name"]=post("arctype_name");
			$data["arctype_parent_id"]=post("arctype_parent_id");
			$data["arctype_is_parent"]=post("arctype_is_parent");
			$data["arctype_depth"]=post("arctype_depth");
			$data["arctype_url"]=post("arctype_url");
			$data["arctype_path"]=post("arctype_path");
			$data["arctype_sort"]=post("arctype_sort");
			$data["arctype_type"]=post("arctype_type");
			$data["arctype_content"]=stripslashes($_POST["arctype_content"]);
			$data["arctype_state"]=post("arctype_state");
			
			$list=M("arctype")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/arctype/arctype'));
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

	public function arctype_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("arctype")->where("arctype_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function arctype_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arctype set arctype_state=1 where arctype_id=".$ids_arr[$i]." ");
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

	public function arctype_detail()
	{
		if(intval(get("arctype_id"))>0)
		{
			$data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["arctype_name"]."栏目");
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