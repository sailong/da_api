<?php
class field_admin_menuModel extends Model{

	function field_admin_menu_list_pro($bigwhere="", $page_size=20, $sort="") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		

		if(get("starttime"))
		{
			$where .=" and field_admin_menu_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime"))
		{
			$where .=" and field_admin_menu_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("field_admin_menu")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ts_user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("field_admin_menu")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	function field_admin_menu_select_pro($bigwhere="",$limit=999999, $sort=" field_admin_menu_sort asc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("field_admin_menu")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		for($i=0;  $i<count($data['item']); $i++)
		{
			if($data["item"][$i]['field_admin_menu_id']!="")
			{
				$data["item"][$i]['sub']=M('field_admin_menu')->where(' field_admin_menu_parent_id='.$data["item"][$i]['field_admin_menu_id'].' ')->order($sort)->select();
				/*
				for($j=0; $j<count($data["item"][$i]['sub']); $j++)
				{
					$if_sub_select=M()->query("select admin_role_menu_id from tbl_admin_role_menu where  field_admin_menu_id='".$data["item"][$i]['sub'][$j]['field_admin_menu_id']."' ");
					$data["item"][$i]['sub'][$j]['if_sub_select']==$if_sub_select[0]['admin_role_menu_id'];
					//print_r($if_sub_select);
				}
				*/

				$if_select=M()->query("select admin_role_menu_id from tbl_admin_role_menu where and field_admin_menu_id='".$data["item"][$i]['field_admin_menu_id']."' ");
				$data["item"][$i]['if_select']==$if_select[0]['admin_role_menu_id'];
				//print_r($if_select);
			}
		}
		$data["total"] = M("field_admin_menu")->where($where.$bigwhere)->count();

		return $data;
	}

	

}
?>