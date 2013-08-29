<?php
/**
 *    #Case		tankuang
 *    #Page		ArcAction.class.php (文章)
 *
 *    @author		Zhang Long
 *    @E-mail		68779953@qq.com
 */
class arcAction extends publicAction
{

	public function _intialize()	
	{
		parent::_intialize();
	}

	public function tj_list()
	{
		$arctype_data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
		$this->assign("arctype_data",$arctype_data);
		$this->assign("title",$arctype_data['arctype_name']);

		if($arctype_data['arctype_id']==4)
		{
			$nav_class="developcenter_nav";
			$nav_name="技术中心";
		}
		else if($arctype_data['arctype_id']==6)
		{
			$nav_class="business_nav";
			$nav_name="商务中心";
		}
		else
		{
			$nav_class="";
			$nav_name="新闻中心";
		}
		$this->assign("nav_class",$nav_class);
		$this->assign("nav_name",$nav_name);

		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$arctype_data['arctype_id']."' and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);


		$list=D("arc")->arc_list_pro(" and arc_is_tj='Y' and arc_state=1 and arctype_id in (select arctype_id from tbl_arctype where arctype_parent_id='".$arctype_data['arctype_id']."'  ) ",9999);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1");
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("seo_title","新闻中心－编辑推荐");
    	$this->display();
	}



	public function product_list()
	{
		$arctype_data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
		$this->assign("arctype_data",$arctype_data);
		$this->assign("title",$arctype_data['arctype_name']);

		if($arctype_data['arctype_parent_id']==4)
		{
			$nav_class="developcenter_nav";
		}
		if($arctype_data['arctype_parent_id']==6)
		{
			$nav_class="business_nav";
		}
		$this->assign("nav_class",$nav_class);

		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$arctype_data['arctype_parent_id']."' and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);

		$list=D("arc")->arc_list_pro(" and (arctype_id='".intval(get("arctype_id"))."' or arctype_id2='".intval(get("arctype_id"))."' or arctype_id3='".intval(get("arctype_id"))."') and arc_state=1 ",9999);
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1");
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("page_title",$arctype_data['arctype_name']);
    	$this->display();
	}


	public function arc_list()
	{
		$arctype_data=M("arctype")->where("arctype_id=".intval(get("arctype_id")))->find();
		$this->assign("arctype_data",$arctype_data);
		$this->assign("title",$arctype_data['arctype_name']);

		if($arctype_data['arctype_parent_id']==4)
		{
			$nav_class="developcenter_nav";
		}
		if($arctype_data['arctype_parent_id']==6)
		{
			$nav_class="business_nav";
		}

		if($arctype_data['arctype_id']==38)
		{
			$nav_class="talentcenter_nav";
		}
		
		$this->assign("nav_class",$nav_class);

		$sub_arctype=D("arctype")->arctype_select_pro(" and arctype_parent_id='".$arctype_data['arctype_parent_id']."' and arctype_type='A' ");
		$this->assign("sub_arctype",$sub_arctype['item']);

		$list=D("arc")->arc_list_pro(" and (arctype_id='".intval(get("arctype_id"))."' or arctype_id2='".intval(get("arctype_id"))."' or arctype_id3='".intval(get("arctype_id"))."') and arc_state=1 ");
		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		//side
		$tj_arc=D("arc")->arc_select_pro(" and arc_is_tj='Y' and arc_state=1",9);
		$this->assign("tj_arc",$tj_arc['item']);
		$pic_arc=D("arc")->arc_select_pro(" and arc_is_pic='Y' and arc_state=1",4);
		$this->assign("pic_arc",$pic_arc['item']);

		$this->assign("page_title",$arctype_data['arctype_name']);
    	$this->display();
	}

	public function arc_add()
	{
		$arc_type=D("arctype")->arctype_admin_tree_pro(" and arctype_parent_id=0 "," and arctype_type='A' ");
		$this->assign("arc_type",$arc_type['item']);
		//print_r($arc_type);

		$this->assign("page_title","添加文章");
    	$this->display();
	}

	public function arc_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arc_name"]=post("arc_name");
			$data["staff_id"]=post("staff_id");
			$data["arctype_id"]=post("arctype_id");
			$data["arc_note"]=post("arc_note");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=post("arc_content");
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_state"]=post("arc_state");
			$data["arc_path"]=post("arc_path");
			$data["arc_addtime"]=time();
			$data["arc_statetime"]=strtotime(post("arc_statetime"));
			
			$list=M("arc")->add($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^添加成功");
			}
			else
			{
				msg_dialog_tip("error^添加失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}


	public function arc_edit()
	{
		if(intval(get("arc_id"))>0)
		{
			$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
			$this->assign("data",$data);

			$arc_type=D("arctype")->arctype_admin_tree_pro(" and arctype_parent_id=0 "," and arctype_type='A' ");
			$this->assign("arc_type",$arc_type['item']);
			
			$this->assign("page_title","修改文章");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function arc_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["arc_id"]=post("arc_id");
			$data["arc_name"]=post("arc_name");
			$data["staff_id"]=post("staff_id");
			$data["arctype_id"]=post("arctype_id");
			$data["arc_note"]=post("arc_note");
			if($_FILES["arc_pic"]["error"]==0)
			{
				$uploadinfo=upload_file("upload/arc");
				$data["arc_pic"]=$uploadinfo[0]["savepath"] . $uploadinfo[0]["savename"];
			}
			$data["arc_source"]=post("arc_source");
			$data["arc_editor"]=post("arc_editor");
			$data["arc_content"]=post("arc_content");
			$data["arc_is_tj"]=post("arc_is_tj");
			$data["arc_state"]=post("arc_state");
			$data["arc_path"]=post("arc_path");
			$data["arc_statetime"]=strtotime(post("arc_statetime"));
			
			$list=M("arc")->save($data);
			if($list!=false)
			{
				msg_dialog_tip("succeed^修改成功");
			}
			else
			{
				msg_dialog_tip("error^修改失败");
			}
		}
		else
		{
			$this->error("参数错误或来路非法","/");
		}

	}

	public function arc_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("arc")->where("arc_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function arc_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_state=1 where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^审核成功";
			
		}
	}


	public function arc_checkno_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_state=2 where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^操作成功";
			
		}
	}

	public function arc_tj_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_tj='Y' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^推荐成功";
			
		}
	}


	public function arc_tjno_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_arc set arc_is_tj='N' where arc_id=".$ids_arr[$i]." ");
			}
			echo "succeed^操作成功";
			
		}
	}



	public function arc_detail()
	{
		if(intval(get("arc_id"))>0)
		{
			$data=M("arc")->where("arc_id=".intval(get("arc_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$side_str=D("arc")->arc_detail_side($data['arctype_id']);
				$this->assign("side_str",$side_str);

				$this->assign("page_title",$data["arc_name"]."文章");
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