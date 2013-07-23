<?php
/**
 *    #Case		kalatai
 *    #Page		DictAction.class.php (字典)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class dictAction extends AdminAuthAction
{

	public function _initialize()	
	{
		parent::_initialize();
	}

	public function dict()
	{
		$list=D("dict")->dict_list_pro();
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$dict_types=D("dict_type")->dict_type_select_pro();
		$this->assign("dict_types",$dict_types['item']);
		//print_r($dict_types);

		$this->assign("page_title","字典");
    	$this->display();
	}

	public function dict_add()
	{
		
		$dict_types=D("dict_type")->dict_type_select_pro();
		$this->assign("dict_types",$dict_types['item']);

		$dict_parent_list=M("dict")->where("dict_parent_id=0 and dict_type='".get("dict_type_id")."' ")->select();
		$this->assign("dict_parent_list",$dict_parent_list);

		$this->assign("page_title","添加字典");
    	$this->display();
	}

	public function dict_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["dict_name"]=post("dict_name");
			$data["dict_value"]=post("dict_value");
			$data["dict_parent_id"]=post("dict_parent_id");
			$data["dict_type"]=post("dict_type");
			$data["dict_sort"]=post("dict_sort");
			
			$list=M("dict")->add($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^添加成功");
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


	public function dict_edit()
	{
		if(intval(get("dict_id"))>0)
		{
			$data=M("dict")->where("dict_id=".intval(get("dict_id")))->find();
			$this->assign("data",$data);

			$dict_parent_list=M("dict")->where("dict_parent_id=0 and dict_type='".get("dict_type_id")."' ")->select();
			$this->assign("dict_parent_list",$dict_parent_list);

			$dict_types=D("dict_type")->dict_type_select_pro();
			$this->assign("dict_types",$dict_types['item']);
			
			$this->assign("page_title","修改字典");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function dict_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["dict_id"]=post("dict_id");
			$data["dict_name"]=post("dict_name");
			$data["dict_parent_id"]=post("dict_parent_id");
			$data["dict_type"]=post("dict_type");
			$data["dict_value"]=post("dict_value");
			$data["dict_sort"]=post("dict_sort");
			
			$list=M("dict")->save($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^修改成功");
			}
			
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function dict_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("dict")->where("dict_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function dict_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_dict set dict_state=1 where dict_id=".$ids_arr[$i]." ");
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

	public function dict_detail()
	{
		if(intval(get("dict_id"))>0)
		{
			$data=M("dict")->where("dict_id=".intval(get("dict_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["dict_name"]."字典");
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