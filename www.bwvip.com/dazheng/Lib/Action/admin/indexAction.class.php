<?php
class indexAction extends AdminAuthAction
{
	public function _initialize()	
	{
		parent::_initialize();
	}

	public function verify() {
        $type	 =	 isset($_GET['type'])?$_GET['type']:'gif';
        import("@.ORG.String");
        import("@.ORG.Image");
        Image::buildImageVerify(4,1,$type,60,27);
    }

    public function index()
	{
		$menu=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=0 and admin_menu_state=1 and admin_menu_id in (select admin_menu_id from tbl_admin_role_menu where admin_role_id='".$_SESSION['admin_role_id']."') ");
		
		for($i=0; $i<count($menu['item']); $i++)
		{
			$url=M()->query("select admin_menu_url from tbl_admin_menu where admin_menu_id in (select admin_menu_id from tbl_admin_role_menu where admin_role_id='".$_SESSION['admin_role_id']."') and admin_menu_parent_id='".$menu['item'][$i]['admin_menu_id']."' ");
			$menu['item'][$i]['default_url']=$url[0]['admin_menu_url'];
		}
		$this->assign("menu",$menu['item']);

		$this->display();

    }

	public function load_left_menu()
	{
		$sub_menu=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=".get("parent_id")." and admin_menu_state=1 and admin_menu_id in (select admin_menu_id from tbl_admin_role_menu where admin_role_id='".$_SESSION['admin_role_id']."')  ");
		$this->assign("sub_menu",$sub_menu['item']);

		$this->display();
    }



	public function admin()
	{
		$list=D("Admin")->admin_select_all_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_add()
	{
		$role=D("Admin_role")->admin_role_select_all_nopage_pro();
		$this->assign("role",$role);
		$this->display();
	}

	public function admin_add_action()
	{

		$data["admin_name"]=post("admin_name");
		$data["admin_password"]=md5(post("admin_password"));
		$data["admin_realname"]=post("admin_realname");
		$data["admin_email"]=post("admin_email");
		$data["admin_role_id"]=post("admin_role_id");
		$data["admin_lasttime"]=time();
		$data["admin_lastip"]=post("admin_lastip");
		$data["admin_addtime"]=time();
		
		$list=M("admin")->add($data);
		if($list!=false)
		{
			$this->success("添加成功");
		}
		else
		{
			$this->error("添加失败");
		}

	}

	public function admin_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("admin")->where("admin_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}



	public function admin_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update ts_app_admin set admin_state=1 where course_id=".$ids_arr[$i]." ");
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



	public function admin_edit()
	{
		if(intval(get("admin_id"))>0)
		{
			$role=D("Admin_role")->admin_role_select_all_nopage_pro();
			$this->assign("role",$role);

			$data=M("admin")->where("admin_id=".intval(get("admin_id")))->find();
			$this->assign("data",$data);

			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function admin_edit_action()
	{

		$data["admin_id"]=post("admin_id");
		$data["admin_name"]=post("admin_name");
		if(post("admin_password_new"))
		{
			$data["admin_password"]=md5(post("admin_password_new"));	
		}
		$data["admin_realname"]=post("admin_realname");
		$data["admin_email"]=post("admin_email");
		$data["admin_role_id"]=post("admin_role_id");
		
		$list=M("admin")->save($data);
		if($list!=false)
		{
			$this->success("修改成功");
		}
		else
		{
			$this->error("修改失败");
		}

	}

	public function admin_detail()
	{
		if(intval(get("admin_id"))>0)
		{
			$data=M("admin")->where("admin_id=".intval(get("admin_id")))->find();
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



	public function admin_role()
	{
		$list=D("Admin_role")->admin_role_select_all_pro();

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_role_add()
	{
		$data=M("admin_role")->where("admin_role_id=".intval(get("admin_role_id")))->find();
		$this->assign("data",$data);

    	$this->display();
	}

	public function admin_role_add_action()
	{

		$data["admin_role_name"]=post("admin_role_name");
		$data["admin_role_content"]=post("admin_role_content");
		$data["event_ids"]=post("event_ids");
		$list=M("admin_role")->add($data);

		if($list!=false)
		{
			$this->success("添加成功");
		}
		else
		{
			$this->error("添加失败");
		}

	}

	public function admin_role_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("admin_role")->where("admin_role_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}

	public function admin_role_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update ts_app_admin_role set admin_role_state=1 where course_id=".$ids_arr[$i]." ");
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

	public function admin_role_edit()
	{
		if(intval(get("admin_role_id"))>0)
		{
			$data=M("admin_role")->where("admin_role_id=".intval(get("admin_role_id")))->find();
			$this->assign("data",$data);

			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function admin_role_edit_action()
	{

		$data["admin_role_id"]=post("admin_role_id");
		$data["admin_role_name"]=post("admin_role_name");
		$data["admin_role_content"]=post("admin_role_content");
		$data["event_ids"]=post("event_ids");
		
		$list=M("admin_role")->save($data);
		if($list!=false)
		{
			$this->success("修改成功");
		}
		else
		{
			$this->error("修改失败");
		}

	}



	public function admin_menu()
	{
		$list=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=0 ");
		$this->assign("list",$list["item"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_menu_add()
	{
		$menu=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=0 ");
		$this->assign("menu",$menu['item']);
    	$this->display();
	}

	public function admin_menu_add_action()
	{

		$data["admin_menu_name"]=post("admin_menu_name");
		$data["admin_menu_parent_id"]=post("admin_menu_parent_id");
		$data["admin_menu_sort"]=post("admin_menu_sort");
		$data["admin_menu_url"]=post("admin_menu_url");
		$data["admin_menu_state"]=post("admin_menu_state");
		
		$list=M("admin_menu")->add($data);
		if($list!=false)
		{
			msg_dialog_tip("succeed^添加成功");
		}
		else
		{
			msg_dialog_tip("error^添加失败");			
		}

	}

	public function admin_menu_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("admin_menu")->where("admin_menu_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function admin_menu_edit()
	{
		if(intval(get("admin_menu_id"))>0)
		{
			$menu=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=0 ");
			$this->assign("menu",$menu['item']);

			$data=M("admin_menu")->where("admin_menu_id=".intval(get("admin_menu_id")))->find();
			$this->assign("data",$data);

			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function admin_menu_edit_action()
	{

		$data["admin_menu_id"]=post("admin_menu_id");
		$data["admin_menu_name"]=post("admin_menu_name");
		$data["admin_menu_parent_id"]=post("admin_menu_parent_id");
		$data["admin_menu_sort"]=post("admin_menu_sort");
		$data["admin_menu_url"]=post("admin_menu_url");
		$data["admin_menu_state"]=post("admin_menu_state");
		
		$list=M("admin_menu")->save($data);
		if($list!=false)
		{
			msg_dialog_tip("succeed^修改成功");
		}
		else
		{
			msg_dialog_tip("error^修改失败");			
		}

	}


	public function admin_role_menu()
	{
		$role=D("Admin_role")->admin_role_select_all_nopage_pro();
		$this->assign("role",$role);

		$list=D("Admin_menu")->admin_menu_select_all_nopage_pro(" and admin_menu_parent_id=0 ");
		$this->assign("list",$list["item"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_role_menu_action()
	{
		if(post("ids"))
		{
			$ids=explode(",",post("ids"));
			$des=M()->execute(" delete from tbl_admin_role_menu where admin_role_id='".post("canshu")."' ");
			for($i=0; $i<count($ids); $i++)
			{
				$res=M()->execute("insert into tbl_admin_role_menu (admin_role_id,admin_menu_id) values ('".post("canshu")."','".$ids[$i]."')");
			}
			echo "succeed^保存成功";
		}
		else
		{
			echo "error^保存失败2";
		}
	}



	public function site()
	{
	
		$data=M("site")->where("site_id=1")->find();
		$this->assign("data",$data);

		$this->display();
	
	}

	public function site_edit_action()
	{

		$data["site_id"]='1';
		$data["site_name"]=post("site_name");
		$data["site_url"]=post("site_url");
		$data["site_keyword"]=post("site_keyword");
		$data["site_description"]=post("site_description");
		$data["site_copyright"]=post("site_copyright");

		$data["site_user_state"]=post("site_user_state");
		
		$list=M("site")->save($data);
		if($list!=false)
		{
			$this->success("修改成功");
		}
		else
		{
			$this->error("修改失败");
		}

	}





}