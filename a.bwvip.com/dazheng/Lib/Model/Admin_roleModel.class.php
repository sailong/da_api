<?php
class Admin_roleModel extends Model{

	function admin_role_select_all_pro($bigwhere="", $page_size=20, $sort="") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		if(get("admin_role_id"))
			{
				$where .=" and admin_role_id='".get("admin_role_id")."' ";
			}
			if(get("admin_role_name"))
			{
				$where .=" and admin_role_name='".get("admin_role_name")."' ";
			}
			if(get("admin_content"))
			{
				$where .=" and admin_content='".get("admin_content")."' ";
			}
			

		if(get("starttime"))
		{
			$where .=" and admin_role_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime"))
		{
			$where .=" and admin_role_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("admin_role")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ts_user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("admin_role")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	function admin_role_select_all_nopage_pro($bigwhere="",$limit=999999, $sort="") 
	{
		
		$where = " 1 ";

		$data=M("admin_role")->where($where.$bigwhere)->order($sort)->limit($limit)->select();

		return $data;
	}

	

}
?>