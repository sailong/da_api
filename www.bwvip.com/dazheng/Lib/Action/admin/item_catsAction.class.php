<?php
/**
 *    #Case		bwvip
 *    #Page		Item_catsAction.class.php (商品分类)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-11-04
 */
class item_catsAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function item_cats()
	{
		$list=D("item_cats")->item_cats_select_all_pro('and is_parent=1');
		$parent_list=D("item_cats")->item_cats_select_pro(" and is_parent=1");
		$parents = array();
		foreach($parent_list["item"] as $key=>$val)
		{
			$parents[$val['item_cats_id']] = $val;
		}
		unset($parent_list);
		
		$field_list = D('field')->field_select_pro();
		
		$fields = array();
		foreach($field_list['item'] as $key=>$val)
		{
			$fields[$val['field_uid']] = $val['field_name'];
		}
		unset($field_list);
		
		$this->assign("fields",$fields);
		$this->assign("parents",$parents);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","商品分类");
    	$this->display();
	}

	public function item_cats_add()
	{
		$app_list=select_field(1,"select");
		$this->assign("app_list",$app_list);
		
		$parent_list=D("item_cats")->item_cats_select_pro(" and is_parent=1");
		$parents = array();
		foreach($parent_list["item"] as $key=>$val)
		{
			$parents[$val['item_cats_id']] = $val;
		}
		unset($parent_list);
		$this->assign("item_cats_id",get('item_cats_id'));
		$this->assign("field_uid",get('field_uid'));
		$this->assign("is_parent",get('is_parent'));
		$this->assign("parents",$parents);
		$this->assign("page_title","添加商品分类");
    	$this->display();
	}

	public function item_cats_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=post("field_uid");
			$data["parent_id"]=post("parent_id");
			$data["is_parent"]=post("is_parent");
			$data["item_cats_name"]=post("item_cats_name");
			$uploadinfo=upload_file("upload/item/");
			$pic_list = array();
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			unset($uploadinfo);
			if($_FILES["item_cats_pic"]["error"]==0)
			{
				$data["item_cats_pic"]=$pic_list['item_cats_pic']["savepath"].$pic_list['item_cats_pic']["savename"];
				/* $data["item_cats_pic"]=$pic_list["item_cats_pic"]["savepath"] . $pic_list["item_cats_pic"]["savename"];
				$img_exp = explode('.',$data["item_cats_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_cats_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_cats_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_cats_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_cats_pic"] = $m_path; */
			}
			$data["item_cats_sort"]=post("item_cats_sort");
			$data["item_cats_addtime"]=time();
			
			$list=M("item_cats")->add($data);
			$this->success("添加成功",U('admin/item_cats/item_cats'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/item_cats/item_cats_add'));
		}

	}


	public function item_cats_edit()
	{
		if(intval(get("item_cats_id"))>0)
		{
			$data=M("item_cats")->where("item_cats_id=".intval(get("item_cats_id")))->find();
			$this->assign("data",$data);
			
			$app_list=select_field(1,"select");
			$this->assign("app_list",$app_list);
			
			$parent_list=D("item_cats")->item_cats_select_pro(" and is_parent=1");
			$parents = array();
			foreach($parent_list["item"] as $key=>$val)
			{
				$parents[$val['item_cats_id']] = $val;
			}
			unset($parent_list);
			
			$this->assign("parents",$parents);
			
			
			$this->assign("page_title","修改商品分类");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function item_cats_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["item_cats_id"]=post("item_cats_id");
			$data["field_uid"]=post("field_uid");
			$data["parent_id"]=post("parent_id");
			$data["is_parent"]=post("is_parent");
			$data["item_cats_name"]=post("item_cats_name");
			
			$uploadinfo=upload_file("upload/item/");
			$pic_list = array();
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			unset($uploadinfo);
			if($_FILES["item_cats_pic"]["error"]==0)
			{
				$data["item_cats_pic"]=$pic_list['item_cats_pic']["savepath"].$pic_list['item_cats_pic']["savename"];
				
				/* $data["item_cats_pic"]=$pic_list["item_cats_pic"]["savepath"] . $pic_list["item_cats_pic"]["savename"];
				$img_exp = explode('.',$data["item_cats_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_cats_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_cats_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_cats_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_cats_pic"] = $m_path; */
				
			}
			$data["item_cats_sort"]=post("item_cats_sort");
			
			$list=M("item_cats")->save($data);
			$this->success("修改成功",U('admin/item_cats/item_cats'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/item_cats/item_cats'));
		}

	}

	public function item_cats_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("item_cats")->where("item_cats_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function item_cats_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_item_cats set item_cats_state=1 where item_cats_id=".$ids_arr[$i]." ");
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

	public function item_cats_detail()
	{
		if(intval(get("item_cats_id"))>0)
		{
			$data=M("item_cats")->where("item_cats_id=".intval(get("item_cats_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["item_cats_name"]."商品分类");
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