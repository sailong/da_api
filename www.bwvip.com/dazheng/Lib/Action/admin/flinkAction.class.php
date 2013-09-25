<?php
 /**
     * kalatai 管理员
     * jack 20120816
     *
     */
class flinkAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function flink()
	{
		$list=D("flink")->flink_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function flink_add()
	{
		$data=M("flink")->where("flink_id=".intval(get("flink_id")))->find();
		$this->assign("data",$data);

    	$this->display();
	}

	public function flink_add_action()
	{

		$data["flink_name"]=post("flink_name");
		$data["flink_url"]=post("flink_url");
		$data["flink_info"]=post("flink_info");
		$data["flink_sort"]=post("flink_sort");
		$data["flink_addtime"]=time();
		
		$list=M("flink")->add($data);
		if($list!=false)
		{
			msg_dialog_tip("succeed^添加成功");
		}
		else
		{
			msg_dialog_tip("error^添加失败");
		}

	}

	public function flink_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("flink")->where("flink_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}



	public function flink_edit()
	{
		if(intval(get("flink_id"))>0)
		{
			$data=M("flink")->where("flink_id=".intval(get("flink_id")))->find();
			$this->assign("data",$data);

			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function flink_edit_action()
	{

		$data["flink_id"]=post("flink_id");
		$data["flink_name"]=post("flink_name");
		$data["flink_url"]=post("flink_url");
		$data["flink_info"]=post("flink_info");
		$data["flink_sort"]=post("flink_sort");


		$list=M("flink")->save($data);
		if($list!=false)
		{
			msg_dialog_tip("succeed^修改成功");
		}
		else
		{
			msg_dialog_tip("error^修改失败");
		}

	}

	public function flink_detail()
	{
		if(intval(get("flink_id"))>0)
		{
			$data=M("flink")->where("flink_id=".intval(get("flink_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);
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