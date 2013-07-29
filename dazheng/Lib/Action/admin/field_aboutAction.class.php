<?php
/**
 *    #Case		bwvip
 *    #Page		Field_aboutAction.class.php (球场介绍)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class field_aboutAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function field_about()
	{
		$list=D("field_about")->field_about_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场介绍");
    	$this->display();
	}

	public function field_about_add()
	{
		$page_list=select_dict(13,"select");
		$this->assign("page_list",$page_list);

		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["about_content"],"about_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign("page_title","添加球场介绍");
    	$this->display();
	}

	public function field_about_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["about_name"]=post("about_name");
			$data["about_type"]=post("about_type");
			$data["about_content"]=stripslashes($_POST["about_content"]);;
			$data["about_tel"]=post("about_tel");
			$data["about_tel2"]=post("about_tel2");
			$data["about_sort"]=post("about_sort");
			$data["about_addtime"]=time();
			
			$list=M("field_about")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/field_about/field_about'));
			}
			else
			{				
				$this->error("添加失败",U('admin/field_about/field_about_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_about/field_about_add'));
		}

	}


	public function field_about_edit()
	{
		if(intval(get("about_id"))>0)
		{
			$page_list=select_dict(13,"select");
			$this->assign("page_list",$page_list);

			$data=M("field_about")->where("about_id=".intval(get("about_id")))->find();
			$this->assign("data",$data);
			
			import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["about_content"],"about_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
			
			$this->assign("page_title","修改球场介绍");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function field_about_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["about_id"]=post("about_id");
			$data["field_uid"]=post("field_uid");
			$data["about_name"]=post("about_name");
			$data["about_type"]=post("about_type");
			$data["about_content"]=stripslashes($_POST["about_content"]);;
			$data["about_tel"]=post("about_tel");
			$data["about_tel2"]=post("about_tel2");
			$data["about_replynum"]=post("about_replynum");
			$data["about_sort"]=post("about_sort");
			
			$list=M("field_about")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/field_about/field_about'));
			}
			else
			{				
				$this->error("修改失败",U('admin/field_about/field_about'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/field_about/field_about'));
		}

	}

	public function field_about_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("field_about")->where("about_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function field_about_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_field_about set field_about_state=1 where about_id=".$ids_arr[$i]." ");
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

	public function field_about_detail()
	{
		if(intval(get("about_id"))>0)
		{
			$data=M("field_about")->where("about_id=".intval(get("about_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["field_about_name"]."球场介绍");
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