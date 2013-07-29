<?php
/**
 *    #Case		bwvip
 *    #Page		QiutongAction.class.php (球童)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class qiutongAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function qiutong()
	{
		$list=D("qiutong")->qiutong_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球童");
    	$this->display();
	}

	public function qiutong_add()
	{

		$this->assign("page_title","添加球童");
    	$this->display();
	}

	public function qiutong_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["qiutong_number"]=post("qiutong_number");
			$data["qiutong_name"]=post("qiutong_name");
			$data["qiutong_name_en"]=post("qiutong_name_en");
			if($_FILES["qiutong_photo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/qiutong/");
				$data["qiutong_photo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["field_uid"]=post("field_uid");
			$data["qiutong_content"]=post("qiutong_content");
			$data["qiutong_addtime"]=time();
			
			$list=M("qiutong")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('admin/qiutong/qiutong'));
			}
			else
			{				
				$this->error("添加失败",U('admin/qiutong/qiutong_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/qiutong/qiutong_add'));
		}

	}


	public function qiutong_edit()
	{
		if(intval(get("qiutong_id"))>0)
		{
			$data=M("qiutong")->where("qiutong_id=".intval(get("qiutong_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改球童");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function qiutong_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["qiutong_id"]=post("qiutong_id");
			$data["qiutong_number"]=post("qiutong_number");
			$data["qiutong_name"]=post("qiutong_name");
			$data["qiutong_name_en"]=post("qiutong_name_en");
			if($_FILES["qiutong_photo"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/qiutong/");
				$data["qiutong_photo"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["field_uid"]=post("field_uid");
			$data["qiutong_content"]=post("qiutong_content");
			
			$list=M("qiutong")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('admin/qiutong/qiutong'));
			}
			else
			{				
				$this->error("修改失败",U('admin/qiutong/qiutong'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('admin/qiutong/qiutong'));
		}

	}

	public function qiutong_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("qiutong")->where("qiutong_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function qiutong_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_qiutong set qiutong_state=1 where qiutong_id=".$ids_arr[$i]." ");
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

	public function qiutong_detail()
	{
		if(intval(get("qiutong_id"))>0)
		{
			$data=M("qiutong")->where("qiutong_id=".intval(get("qiutong_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["qiutong_name"]."球童");
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