<?php
/**
 *    #Case		bwvip
 *    #Page		Item_cartAction.class.php (购物车)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-11-04
 */
class item_cartAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function item_cart()
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
	
		//上级商品列表
		$parent_item_list=D("item")->item_select_pro(" and parent_id=0");
		$parent_items = array();
		foreach($parent_item_list['item'] as $key=>$val)
		{
			$parent_items[$val['item_id']] = $val['item_name'];
		}
		$this->assign("parent_items",$parent_items);
		
		//所属分类列表
		$parent_cats_list = D("item_cats")->item_cats_select_pro();
		$parent_cats = array();
		foreach($parent_cats_list['item'] as $key=>$val)
		{
			$parent_cats[$val['item_cats_id']] = $val['item_cats_name'];
		}
		
		$this->assign("parent_cats",$parent_cats);
	
		$list=D("item_cart")->item_cart_list_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","购物车");
    	$this->display();
	}

	public function item_cart_add()
	{
		
		$this->assign("page_title","添加购物车");
    	$this->display();
	}

	public function item_cart_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["uid"]=post("uid");
			$data["parent_id"]=post("parent_id");
			$data["item_id"]=post("item_id");
			$data["item_name"]=post("item_name");
			$data["item_buyinfo"]=post("item_buyinfo");
			$data["item_price"]=post("item_price");
			$data["item_num"]=post("item_num");
			$data["item_cart_addtime"]=time();
			
			$list=M("item_cart")->add($data);
			$this->success("添加成功",U('admin/item_cart/item_cart'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/item_cart/item_cart_add'));
		}

	}


	public function item_cart_edit()
	{
		if(intval(get("item_cart_id"))>0)
		{
			$data=M("item_cart")->where("item_cart_id=".intval(get("item_cart_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改购物车");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function item_cart_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["item_cart_id"]=post("item_cart_id");
			$data["field_uid"]=post("field_uid");
			$data["uid"]=post("uid");
			$data["parent_id"]=post("parent_id");
			$data["item_id"]=post("item_id");
			$data["item_name"]=post("item_name");
			$data["item_buyinfo"]=post("item_buyinfo");
			$data["item_price"]=post("item_price");
			$data["item_num"]=post("item_num");
			
			$list=M("item_cart")->save($data);
			$this->success("修改成功",U('admin/item_cart/item_cart'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/item_cart/item_cart'));
		}

	}

	public function item_cart_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("item_cart")->where("item_cart_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function item_cart_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_item_cart set item_cart_state=1 where item_cart_id=".$ids_arr[$i]." ");
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

	public function item_cart_detail()
	{
		if(intval(get("item_cart_id"))>0)
		{
			$data=M("item_cart")->where("item_cart_id=".intval(get("item_cart_id")))->find();
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
			
				//上级商品列表
				$parent_item_list=D("item")->item_select_pro(" and parent_id=0");
				$parent_items = array();
				foreach($parent_item_list['item'] as $key=>$val)
				{
					$parent_items[$val['item_id']] = $val['item_name'];
				}
				$this->assign("parent_items",$parent_items);
				
				
				$this->assign("data",$data);

				$this->assign("page_title",$data["item_cart_name"]."购物车");
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