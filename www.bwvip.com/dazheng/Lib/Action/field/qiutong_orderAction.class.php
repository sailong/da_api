<?php
/**
 *    #Case		bwvip
 *    #Page		Qiutong_orderAction.class.php (球童预约明细)
 *
 *    @author		Zhang Long
 *    @E-mail		123695069@qq.com
 */
class qiutong_orderAction extends field_publicAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function qiutong_order()
	{
	    $where = '';
	    $k = get('k');
	    $where = " a.field_uid='1186'";
	    if(!empty($k)) {
	        $where .= " and (b.qiutong_name like '%{$k}%' or b.qiutong_name_en like '%{$k}%') ";
	    }
		$list=D("qiutong_order")->qiutong_order_list_pro($where);
        //echo D()->getLastSql();
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
		$this->assign("page_title","球童预约明细");
    	$this->display();
	}

	public function qiutong_order_add()
	{

		$this->assign("page_title","添加球童预约明细");
    	$this->display();
	}

	public function qiutong_order_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["uid"]=post("uid");
			$data["qiutong_id"]=post("qiutong_id");
			$data["field_uid"]=1186;//post("field_uid");
			$data["qiutong_order_date"]=strtotime(post("qiutong_order_date"));
			$data["qiutong_order_teetime"]=post("qiutong_order_teetime");
			$data["qiutong_order_state"]=post("qiutong_order_state");
			$data["qiutong_order_addtime"]=time();
			
			$list=M("qiutong_order")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('field/qiutong_order/qiutong_order'));
			}
			else
			{				
				$this->error("添加失败",U('field/qiutong_order/qiutong_order_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/qiutong_order/qiutong_order_add'));
		}

	}


	public function qiutong_order_edit()
	{
		if(intval(get("qiutong_order_id"))>0)
		{
			$data=M("qiutong_order")->where("qiutong_order_id=".intval(get("qiutong_order_id")))->find();
			$this->assign("data",$data);
			
			$this->assign("page_title","修改球童预约明细");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function qiutong_order_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["qiutong_order_id"]=post("qiutong_order_id");
			$data["uid"]=post("uid");
			$data["qiutong_id"]=post("qiutong_id");
			$data["field_uid"]=1186;//post("field_uid");
			$data["qiutong_order_date"]=strtotime(post("qiutong_order_date"));
			$data["qiutong_order_teetime"]=post("qiutong_order_teetime");
			$data["qiutong_order_state"]=post("qiutong_order_state")=='on' ? 1 : 0;
			$data["qiutong_order_addtime"]=time();
			
			$list=M("qiutong_order")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('field/qiutong_order/qiutong_order'));
			}
			else
			{				
				$this->error("修改失败",U('field/qiutong_order/qiutong_order'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('field/qiutong_order/qiutong_order'));
		}

	}

	public function qiutong_order_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("qiutong_order")->where("qiutong_order_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function qiutong_order_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_qiutong_order set qiutong_order_state=1 where qiutong_order_id=".$ids_arr[$i]." ");
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

	public function qiutong_order_detail()
	{
		if(intval(get("qiutong_order_id"))>0)
		{
			$data=M("qiutong_order")->where("qiutong_order_id=".intval(get("qiutong_order_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["qiutong_order_name"]."球童预约明细");
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