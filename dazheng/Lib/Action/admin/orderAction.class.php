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
				
				
				$data['order_money'] =$data['order_money']/100;
				//echo '<pre>';
				//var_dump($data['item_ids']);
				$item_list = M('item')->where("item_id in(".$data['item_ids'].")")->select();
				//var_dump($item_list);
				foreach($item_list as $key=>$val){
					$parent_item_ids[$val['parent_id']] = $val['parent_id'];
				}
				//var_dump($parent_item_ids);
				$parent_item_list = M('item')->where("item_id in('".implode("','",$parent_item_ids)."')")->select();
				//var_dump($parent_item_list);
				foreach($parent_item_list as $key=>$val){
					$parent_item_lists[$val['item_id']] = $val['item_name'];
				}
				unset($parent_item_list);
				foreach($item_list as $key=>$val){
					$val['parent_name'] = $parent_item_lists[$val['parent_id']];
					$item_lists[$val['item_id']] = $val;
				}
				unset($parent_item_lists);
				
				$item_ids = explode(',',$data['item_ids']);
				
				$item_names = explode(',',$data['item_names']);
				
				$item_nums = explode(',',$data['item_nums']);
				
				$item_info = array();
				//echo '<pre>';
				for($i=0;$i<count($item_ids);$i++){
					$item_parent_id = $item_lists[$item_ids[$i]]['parent_id'];
					$item_info[$item_parent_id]['parent_name'] = $item_lists[$item_ids[$i]]['parent_name'];
					$item_info[$item_parent_id]['parent_id'] = $item_parent_id;
					$item_info[$item_parent_id]['sub_list'][$item_ids[$i]]['item_id'] = $item_ids[$i];
					//$item_info[$item_parent_id]['sub_list'][$item_ids[$i]]['parent_item_name'] = $item_lists[$item_ids[$i]]['parent_name'];
					$item_info[$item_parent_id]['sub_list'][$item_ids[$i]]['item_name'] = $item_names[$i];
					$item_info[$item_parent_id]['sub_list'][$item_ids[$i]]['item_num'] = $item_nums[$i];
				}
				
				//var_dump($item_info);
				unset($item_ids,$item_names,$item_lists);
				
				$this->assign("item_info",$item_info);
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
	
	public function item_detail_ext()
	{
		
		if(intval(get("item_id"))>0 && get("order_id") != '')
		{
			$item_info = M('item')->where("item_id='".get("item_id")."'")->find();
			
			$ext_table_name = $item_info['ext_table_name'];
			$ext_name = end(explode('_',$ext_table_name));
			$data=M()->table($ext_table_name)->where("order_id=".intval(get("order_id")))->find();
			//echo $ext_table_name;die;
			if(!empty($data))
			{
				$order_item_info = explode(',',$data['watch_date']);
				$data['order_detail'] = '';
				foreach($order_item_info as $key=>$val){
					$order_item_detail = explode('|',$val);
					$item_id = $order_ticket_detail[0];
					$item_nums = $order_ticket_detail[1];
					$item_info = M('item')->where("item_id='{$ticket_id}'")->find();
					$data['item_detail'] .= $item_info['item_name'].' '.$item_nums.'张<br/>';
				}
				$event_info = M('event')->where("event_id='".$data['event_id']."'")->find();
				
				/* var_dump($data);die; */
				//echo $ext_name.'_item_detail_ext';
				$this->assign("event_info",$event_info);
				$this->assign("data",$data);

				$this->assign("page_title","附加信息");
				$this->display("{$ext_name}_item_detail_ext");
			}
			else
			{
				//$this->error("您该问的信息不存在1");
				echo '您该问的信息不存在';
			}
			
		}
		else
		{
			echo '您该问的信息不存在';
		}

	}


	

}
?>