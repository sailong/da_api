<?php
/**
 *    #Case		bwvip
 *    #Page		OrderAction.class.php (订单)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-11-04
 */
class orderAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function order()
	{
	
		//球场列表
		$field_list = D('field')->field_select_pro();
		$fields = array();
		foreach($field_list['item'] as $key=>$val)
		{
			$fields[$val['field_uid']] = $val['field_name'];
		}
		unset($field_list);
		$this->assign("fields",$fields);
	
		$list=D("order")->order_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","订单");
    	$this->display();
	}

	public function order_add()
	{
		
		$this->assign("page_title","添加订单");
    	$this->display();
	}

	public function order_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["uid"]=post("uid");
			$data["order_number"]=post("order_number");
			$data["order_sn"]=post("order_sn");
			$data["item_ids"]=post("item_ids");
			$data["item_names"]=post("item_names");
			$data["order_money"]=post("order_money");
			$data["order_status"]=post("order_status");
			$data["order_paytime"]=strtotime(post("order_paytime"));
			$data["order_realname"]=post("order_realname");
			$data["order_mobile"]=post("order_mobile");
			$data["order_post"]=post("order_post");
			$data["order_address"]=post("order_address");
			$data["order_addtime"]=time();
			
			$list=M("order")->add($data);
			$this->success("添加成功",U('admin/order/order'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/order/order_add'));
		}

	}


	public function order_edit()
	{
		if(intval(get("order_id"))>0)
		{
			$data=M("order")->where("order_id=".intval(get("order_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改订单");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function order_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["order_id"]=post("order_id");
			$data["field_uid"]=post("field_uid");
			$data["uid"]=post("uid");
			$data["order_number"]=post("order_number");
			$data["order_sn"]=post("order_sn");
			$data["item_ids"]=post("item_ids");
			$data["item_names"]=post("item_names");
			$data["order_money"]=post("order_money");
			$data["order_status"]=post("order_status");
			$data["order_paytime"]=strtotime(post("order_paytime"));
			$data["order_realname"]=post("order_realname");
			$data["order_mobile"]=post("order_mobile");
			$data["order_post"]=post("order_post");
			$data["order_address"]=post("order_address");
			
			$list=M("order")->save($data);
			$this->success("修改成功",U('admin/order/order'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/order/order'));
		}

	}

	public function order_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("order")->where("order_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function order_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_order set order_state=1 where order_id=".$ids_arr[$i]." ");
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

	public function order_detail()
	{
		if(intval(get("order_id"))>0)
		{
			$data=M("order")->where("order_id=".intval(get("order_id")))->find();
			if(!empty($data))
			{
				//球场列表
				$field_list = D('field')->field_select_pro();
				$fields = array();
				foreach($field_list['item'] as $key=>$val)
				{
					$fields[$val['field_uid']] = $val['field_name'];
				}
				unset($field_list);
				$this->assign("fields",$fields);
				
				$this->assign("data",$data);

				$this->assign("page_title",$data["order_name"]."订单");
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