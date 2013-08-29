<?php
/**
 *    #Case		bwvip
 *    #Page		piao_admin_menuAction.class.php (球场管理菜单)
 *
 *    @Author		Zhang Long
 *    @E-mail		123695069@qq.com
 *    @Date			2013-05-28
 */
class piao_admin_menuAction extends piao_publicAction
{

	public function _initialize()
	{
		parent::_initialize();
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

	public function piao_admin_menu_add()
	{
		$menu=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=0 ");
		$this->assign('menu',$menu['item']);
		
		$this->assign("page_title","添加球场权限菜单");
    	$this->display();
	}

	public function piao_admin_menu_add_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["piao_admin_menu_name"]=post("piao_admin_menu_name");
			$data["piao_admin_menu_parent_id"]=post("piao_admin_menu_parent_id");
			$data["piao_admin_menu_url"]=post("piao_admin_menu_url");
			$data["piao_admin_menu_sort"]=post("piao_admin_menu_sort");
			$data["piao_admin_menu_state"]=post("piao_admin_menu_state");
			$data["piao_uid"]=$_SESSION['piao_uid'];
			
			$list=M("piao_admin_menu")->add($data);
			if($list!=false)
			{
				$this->success("添加成功",U('piao/piao_admin_menu/piao_admin_menu'));
			}
			else
			{				
				$this->error("添加失败",U('piao/piao_admin_menu/piao_admin_menu_add'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('piao/piao_admin_menu/piao_admin_menu_add'));
		}

	}


	public function piao_admin_menu_edit()
	{
		if(intval(get("piao_admin_menu_id"))>0)
		{
			$menu=D("piao_admin_menu")->piao_admin_menu_select_pro(" and piao_admin_menu_parent_id=0 ");
			$this->assign('menu',$menu['item']);
		
			$data=M("piao_admin_menu")->where("piao_admin_menu_id=".intval(get("piao_admin_menu_id")))->find();
			$this->assign("data",$data);
			
			
			
			$this->assign("page_title","修改球场权限菜单");
			$this->display();
		}
		else
		{
			$this->error("您该问的信息不存在");
		}
	}

	public function piao_admin_menu_edit_action()
	{
		if(M()->autoCheckToken($_POST))
		{
			$data["piao_admin_menu_id"]=post("piao_admin_menu_id");
			$data["piao_admin_menu_name"]=post("piao_admin_menu_name");
			$data["piao_admin_menu_parent_id"]=post("piao_admin_menu_parent_id");
			$data["piao_admin_menu_url"]=post("piao_admin_menu_url");
			$data["piao_admin_menu_sort"]=post("piao_admin_menu_sort");
			$data["piao_admin_menu_state"]=post("piao_admin_menu_state");
			$data["piao_uid"]=post("piao_uid");
			
			$list=M("piao_admin_menu")->save($data);
			if($list!=false)
			{
				$this->success("修改成功",U('piao/piao_admin_menu/piao_admin_menu'));
			}
			else
			{				
				$this->error("修改失败",U('piao/piao_admin_menu/piao_admin_menu'));
			}
		}
		else
		{
			$this->error("不能重复提交",U('piao/piao_admin_menu/piao_admin_menu'));
		}

	}

	public function piao_admin_menu_delete_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M("piao_admin_menu")->where("piao_admin_menu_id=".$ids_arr[$i])->delete();
			}
			echo "succeed^删除成功";
		}
	}


	public function piao_admin_menu_check_action()
	{
		if(post("ids"))
		{
			$ids_arr=explode(",",post("ids"));
			for($i=0; $i<count($ids_arr); $i++)
			{
				$res=M()->execute("update tbl_piao_admin_menu set piao_admin_menu_state=1 where piao_admin_menu_id=".$ids_arr[$i]." ");
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

	public function piao_admin_menu_detail()
	{
		if(intval(get("piao_admin_menu_id"))>0)
		{
			$data=M("piao_admin_menu")->where("piao_admin_menu_id=".intval(get("piao_admin_menu_id")))->find();
			if(!empty($data))
			{
				$this->assign("data",$data);

				$this->assign("page_title",$data["piao_admin_menu_name"]."球场权限菜单");
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