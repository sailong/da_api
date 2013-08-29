<?php
class piao_adminModel extends Model{

	function admin_select_all_pro($bigwhere="", $page_size=20, $sort="") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		if(get("k"))
		{
			$where .=" and admin_user like '%".get("k")."%' ";
		}
		
		if(get("starttime"))
		{
			$where .=" and admin_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime"))
		{
			$where .=" and admin_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("piao_admin")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{	/*
			if($data["item"][$i]["admin_role_id"]!="")
			{
				$user=M()->query("select admin_role_name from tbl_field_admin_role where  admin_role_id='".$data["item"][$i]["admin_role_id"]."' ");
				$data["item"][$i]["admin_role_name"]=$user[0]["admin_role_name"];
			}
			*/
		}
		$data["total"] = M("piao_admin")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	function admin_select_all_nopage_pro($bigwhere="",$limit=999999, $sort="") 
	{
		
		$where = " 1 ";

		$data=M("piao_admin")->where($where.$bigwhere)->order($sort)->limit($limit)->select();

		return $data;
	}

	

}
?>