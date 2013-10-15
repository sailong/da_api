<?php
/**
 *    #Case		bwvip
 *    #Page		indexAction.class.php (首文件)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class indexAction extends piao_publicAction
{
	public function _initialize()
	{
		parent::_initialize();
	}
	
	
	
	public function index()
	{
		$menu=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=0 and piao_admin_menu_state=1 and piao_admin_menu_id in (select admin_menu_id from tbl_piao_admin_role_menu where admin_role_id='".$_SESSION['piao_admin_role_id']."') ");
		
		for($i=0; $i<count($menu['item']); $i++)
		{
			$url=M()->query("select piao_admin_menu_url from tbl_piao_admin_menu where piao_admin_menu_parent_id='".$menu['item'][$i]['piao_admin_menu_id']."' ");
			$menu['item'][$i]['default_url']=$url[0]['piao_admin_menu_url'];
		}
		$this->assign("menu",$menu['item']);
			
		$this->display();
	}
	
	
	
	public function load_left_menu()
	{
		$parent_id=get("parent_id");
		if(!$parent_id)
		{
			$parent_id=0;
		}
		$sub_menu=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=".$parent_id." and piao_admin_menu_state=1 and piao_admin_menu_id in (select admin_menu_id from tbl_piao_admin_role_menu where admin_role_id='".$_SESSION['piao_admin_role_id']."')");
		$this->assign("sub_menu",$sub_menu['item']);
		
		//print_r($sub_menu);
		
		$this->display();
    }
	
	
	public function piao_admin_menu()
	{
		$list=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=0 ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);

		$this->assign("page_title","球场权限菜单");
    	$this->display();
	}
	
	
	
	
	//new add
	public function admin()
	{
		$list=D("piao_admin")->admin_select_all_pro(" and event_id='".$_SESSION['event_id']."' ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_add()
	{
		$role=D("piao_admin_role")->admin_role_select_all_nopage_pro(" and event_id='".$_SESSION['event_id']."' ");
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
		$data["event_id"]=$_SESSION['event_id'];
		$data["admin_addtime"]=time();
		
		$list=M("piao_admin")->add($data);
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
				$res=M("piao_admin")->where("admin_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}
	
	
	public function admin_edit()
	{
		if(intval(get("admin_id"))>0)
		{
			$role=D("piao_admin_role")->admin_role_select_all_nopage_pro(" and event_id='".$_SESSION['event_id']."'  ");
			$this->assign("role",$role);

			$data=M("piao_admin")->where("admin_id=".intval(get("admin_id")))->find();
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
		
		$list=M("piao_admin")->save($data);
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
			$data=M("piao_admin")->where("admin_id=".intval(get("admin_id")))->find();
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
		$list=D("piao_admin_role")->admin_role_select_all_pro(" and event_id='".$_SESSION['event_id']."' ");

		$this->assign("list",$list["item"]);
		$this->assign("pages",$list["pages"]);
		$this->assign("total",$list["total"]);
    	$this->display();
	}

	public function admin_role_add()
	{
		$data=M("piao_admin_role")->where("admin_role_id=".intval(get("admin_role_id")))->find();
		$this->assign("data",$data);

    	$this->display();
	}

	public function admin_role_add_action()
	{

		$data["admin_role_name"]=post("admin_role_name");
		$data["admin_role_content"]=post("admin_role_content");
		$data["event_id"]=$_SESSION['event_id'];
		$list=M("piao_admin_role")->add($data);

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
				$res=M("piao_admin_role")->where("admin_role_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function admin_role_edit()
	{
		if(intval(get("admin_role_id"))>0)
		{
			$data=M("piao_admin_role")->where("admin_role_id=".intval(get("admin_role_id")))->find();
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
		
		$list=M("piao_admin_role")->save($data);
		if($list!=false)
		{
			$this->success("修改成功");
		}
		else
		{
			$this->error("修改失败");
		}

	}
	
	
	
	public function admin_role_menu()
	{
		$role=D("piao_admin_role")->admin_role_select_all_nopage_pro(" and event_id='".$_SESSION['event_id']."' ");
		$this->assign("role",$role);
		
		
		$list=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=0    ");
	
		$this->assign("list",$list["item"]);
		$this->assign("total",$list["total"]);
		
    	$this->display();
	}
	
	

	public function admin_role_menu_action()
	{
		if(post("ids"))
		{
			$ids=explode(",",post("ids"));
			$des=M()->execute(" delete from tbl_piao_admin_role_menu where admin_role_id='".post("canshu")."' ");
			for($i=0; $i<count($ids); $i++)
			{
				$res=M()->execute("insert into tbl_piao_admin_role_menu (admin_role_id,admin_menu_id,event_id) values ('".post("canshu")."','".$ids[$i]."','".$_SESSION['event_id']."')");
			}
			//echo "succeed^insert into tbl_piao_admin_role_menu (admin_role_id,admin_menu_id,event_id) values ('".post("canshu")."','".$ids[$i]."','".$_SESSION['event_id']."')";
			echo "succeed^保存成功";
		}
		else
		{
			//echo "insert into tbl_piao_admin_role_menu (admin_role_id,admin_menu_id,event_id) values ('".post("canshu")."','".$ids[$i]."','".$_SESSION['event_id']."')";
			echo "error^保存失败2";
		}
	}






}
?>