<?php
/**
 *    #Case		bwvip
 *    #Page		ItemAction.class.php (商品)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-11-04
 */
class itemAction extends AdminAuthAction
{

	public function _basic()	
	{
		parent::_basic();
	}

	public function item()
	{
		/* //球场列表
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
		
		$this->assign("parent_cats",$parent_cats); */
		
		$list=D("item")->item_select_page_pro(' and parent_id=0');
		
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","商品");
    	$this->display();
	}

	public function item_add()
	{
		import("@.ORG.editor");  //导入类
		$editor=new editor("400px","700px",$data["item_content"],"item_content");     //创建一个对象
		$a=$editor->createEditor();   //返回编辑器
		$b=$editor->usejs();             //js代码
		
		//球场客户端列表
		$app_list=select_field(1,"select");
		
		//一级商品列表
		$parent_item_list=D("item")->item_select_pro(" and parent_id=0");
		
		//一级分类列表
		$parent_cats_list = D("item_cats")->item_cats_select_all_pro(" and is_parent=0");
		
		$event_select=D('event')->event_select_pro(" ");
		
		$this->assign('event_select',$event_select['item']);
		
		$this->assign('item_id',get('item_id'));
		$this->assign('field_uid',get('field_uid'));
		$this->assign('item_cats_id',get('item_cats_id'));
		$this->assign('item_type',get('item_type'));
		
		$this->assign('usejs',$b);     //输出到html
		$this->assign('editor',$a);
		$this->assign("app_list",$app_list);
		$this->assign('parent_item_list',$parent_item_list['item']);
		$this->assign('parent_cats_list',$parent_cats_list['item']);
		$this->assign("page_title","添加商品");
    	$this->display();
	}

	public function item_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["field_uid"]=0;//post("field_uid");
			
			if(post("item_type") == 'ticket'){
				//门票字段start
				$data["event_id"]=post("event_id");
				$data["fenzhan_id"]=post("fenzhan_id");
				$data["ticket_ren_num"]=post("ticket_ren_num");
				$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
				$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
				$data["ticket_type"]=post("ticket_type");
				$data["ticket_times"]=post("ticket_times");
				$data["ticket_is_zengsong"]=post("ticket_is_zengsong");
				//门票字段end
			}
			
			
			$data["parent_id"]=post("parent_id");
			$data["item_cats_id"]=post("item_cats_id");
			$data["item_type"]=post("item_type");
			$data["item_type_id"]=post("item_type_id");
			$data["item_name"]=post("item_name");
			$data["item_price"]=post("item_price")*100;
			$data["item_price_old"]=post("item_price_old")*100;
			$data["item_num"]=post("item_num");
			$data["item_num_canbuy"]=post("item_num_canbuy");
			$data["item_num_total"]=post("item_num_total");
			$uploadinfo=upload_file("upload/item/");
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			unset($uploadinfo);
			if($_FILES["item_pic"]["error"]==0)
			{
				$data["item_pic"]=$pic_list["item_pic"]["savepath"] . $pic_list["item_pic"]["savename"];
				
				/* $img_exp = explode('.',$data["item_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_pic"] = $m_path;
				$data["item_pic_small"]=$s_path; */
			}
			if($_FILES["item_pic_small"]["error"]==0)
			{
				$data["item_pic_small"]=$pic_list["item_pic_small"]["savepath"] . $pic_list["item_pic_small"]["savename"];
				
				/* $img_exp = explode('.',$data["item_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_pic"] = $m_path;
				$data["item_pic_small"]=$s_path; */
			}
			if($_FILES["item_pic_bottom"]["error"]==0)
			{
				$data["item_pic_bottom"]=$pic_list["item_pic_bottom"]["savepath"] . $pic_list["item_pic_bottom"]["savename"];
				/* $img_exp = explode('.',$data["item_pic_bottom"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic_bottom"];
				$s_img = $o_img.'_s.'.$ext_end;
				$s_path = $data["item_pic_bottom"].'_s.'.$ext_end;
				com_thumb($o_img, $s_img,'', 1280, 1280);
				$data["item_pic_bottom"] = $s_path; */
			}
			$data["item_intro"]=post("item_intro");
			$data["item_content"]=stripslashes($_POST["item_content"]);;
			$data["item_sort"]=post("item_sort");
			$data["item_addtime"]=time();
			$data["ext_table_name"]=post("ext_table_name");
			
			$list=M("item")->add($data);
			$this->success("添加成功",U('admin/item/item'));
		}
		else
		{
			$this->error("不能重复提交",U('admin/item/item_add'));
		}

	}


	public function item_edit()
	{
		if(intval(get("item_id"))>0)
		{
			$data=M("item")->where("item_id=".intval(get("item_id")))->find();
			$data['item_price'] = $data['item_price'] ? $data['item_price'] : 0;
			$data['item_price_old'] = $data['item_price_old'] ? $data['item_price_old'] : 0;
			$data['item_price'] = $data['item_price']/100;
			$data['item_price_old'] = $data['item_price_old']/100;
			$this->assign("data",$data);
			
			import("@.ORG.editor");  //导入类
			$editor=new editor("400px","700px",$data["item_content"],"item_content");     //创建一个对象
			$a=$editor->createEditor();   //返回编辑器
			$b=$editor->usejs();             //js代码
			$this->assign('usejs',$b);     //输出到html
			$this->assign('editor',$a);
			
			
			//球场客户端列表
			$app_list=select_field(1,"select");
			
			//一级商品列表
			$parent_item_list=D("item")->item_select_pro(" and parent_id=0");
			
			//一级分类列表
			$parent_cats_list = D("item_cats")->item_cats_select_all_pro(" and is_parent=0");
			
			$event_select=D('event')->event_select_pro(" ");
			$this->assign('event_select',$event_select['item']);
			
			$this->assign("app_list",$app_list);
			$this->assign('parent_item_list',$parent_item_list['item']);
			$this->assign('parent_cats_list',$parent_cats_list['item']);
			$this->assign("page_title","修改商品");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function item_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["item_id"]=post("item_id");
			
			if(post("item_type") == 'ticket'){
				//门票字段start
				$data["event_id"]=post("event_id");
				$data["fenzhan_id"]=post("fenzhan_id");
				$data["ticket_ren_num"]=post("ticket_ren_num");
				$data["ticket_starttime"]=strtotime(post("ticket_starttime"));
				$data["ticket_endtime"]=strtotime(post("ticket_endtime"));
				$data["ticket_type"]=post("ticket_type");
				$data["ticket_times"]=post("ticket_times");
				$data["ticket_is_zengsong"]=post("ticket_is_zengsong");
				//门票字段end
			}
			
			
			$data["field_uid"]=0;//post("field_uid");
			$data["parent_id"]=post("parent_id");
			$data["item_cats_id"]=post("item_cats_id");
			$data["item_type"]=post("item_type");
			$data["item_type_id"]=post("item_type_id");
			$data["item_name"]=post("item_name");
			$data["item_price"]=post("item_price")*100;
			$data["item_price_old"]=post("item_price_old")*100;
			$data["item_num"]=post("item_num");
			$data["item_num_canbuy"]=post("item_num_canbuy");
			$data["item_num_total"]=post("item_num_total");
			$uploadinfo=upload_file("upload/item/");
			$pic_list = array();
			foreach($uploadinfo as $key=>$val){
				$pic_list[$val['up_name']] = $val;
			}
			unset($uploadinfo);
			if($_FILES["item_pic"]["error"]==0)
			{
				$data["item_pic"]=$pic_list["item_pic"]["savepath"] . $pic_list["item_pic"]["savename"];
				/* $img_exp = explode('.',$data["item_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_pic"] = $m_path;
				$data["item_pic_small"]=$s_path; */
			}
			if($_FILES["item_pic_small"]["error"]==0)
			{
				$data["item_pic_small"]=$pic_list["item_pic_small"]["savepath"] . $pic_list["item_pic_small"]["savename"];
				
				/* $img_exp = explode('.',$data["item_pic"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic"];
				$m_img = $o_img.'_m.'.$ext_end;
				$s_img = $o_img.'_s.'.$ext_end;
				
				$m_path = $data["item_pic"].'_m.'.$ext_end;
				
				$s_path = $data["item_pic"].'_s.'.$ext_end;
				com_thumb($o_img, $m_img,'', 120, 120);
				com_thumb($o_img, $s_img,'', 60, 60);
				$data["item_pic"] = $m_path;
				$data["item_pic_small"]=$s_path; */
			}
			if($_FILES["item_pic_bottom"]["error"]==0)
			{
				$data["item_pic_bottom"]=$pic_list["item_pic_bottom"]["savepath"] . $pic_list["item_pic_bottom"]["savename"];
				/* $img_exp = explode('.',$data["item_pic_bottom"]);
				$ext_end = end($img_exp);
				$o_img = WEB_ROOT_PATH.'/'.$data["item_pic_bottom"];
				$s_img = $o_img.'_s.'.$ext_end;
				$s_path = $data["item_pic_bottom"].'_s.'.$ext_end;
				com_thumb($o_img, $s_img,'', 1280, 1280);
				$data["item_pic_bottom"] = $s_path; */
			}
			$data["item_intro"]=post("item_intro");
			$data["item_content"]=stripslashes($_POST["item_content"]);;
			$data["item_sort"]=post("item_sort");
			$data["ext_table_name"]=post("ext_table_name");
			M("item")->where("parent_id='".$data["item_id"]."'")->save(array('ext_table_name'=>$data["ext_table_name"],'event_id'=>$data["event_id"],'fenzhan_id'=>$data["fenzhan_id"]));
			$list=M("item")->save($data);
			$this->success("修改成功",U('admin/item/item'));			
		}
		else
		{
			$this->error("不能重复提交",U('admin/item/item'));
		}

	}

	public function item_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("item")->where("item_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}

	public function fenzhan_list()
	{
		$event_id = get('event_id');
		$fenzhan_list = M('fenzhan')->where("event_id='{$event_id}'")->select();
		//echo M()->getLastSql();
		/* foreach($fenzhan_list as $key=>$val){
			$return_list[$val['fenzhan_id']] = $val['fenzhan_name'];
		} */
		//var_dump($fenzhan_list);
		if($fenzhan_list){
			$this->ajaxReturn($fenzhan_list,'成功',1);
		}
		
		$this->ajaxReturn(null,'失败',0);
		
	}
	public function item_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_item set item_state=1 where =".$ids_arr[$i]." ");
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

	public function item_detail()
	{
		if(intval(get("item_id"))>0)
		{
			$data=M("item")->where("item_id=".intval(get("item_id")))->find();
			if(!empty($data))
			{
				$data['item_price'] = $data['item_price'] ? $data['item_price'] : 0;
				$data['item_price_old'] = $data['item_price_old'] ? $data['item_price_old'] : 0;
				$data['item_price'] = $data['item_price']/100;
				$data['item_price_old'] = $data['item_price_old']/100;
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
				$parent_cats_list = D("item_cats")->item_cats_select_pro(" and is_parent=1");
				$parent_cats = array();
				foreach($parent_cats_list['item'] as $key=>$val)
				{
					$parent_cats[$val['item_cats_id']] = $val['item_cats_name'];
				}
				$this->assign("parent_cats",$parent_cats);
				$this->assign("data",$data);

				$this->assign("page_title",$data["item_name"]."商品");
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