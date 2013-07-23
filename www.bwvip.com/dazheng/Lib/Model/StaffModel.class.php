<?php
class StaffModel extends Model{

	function staff_select_all_pro($bigwhere="", $page_size=20, $sort="") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("company_id"))
		{
			$where .=" and company_id=".get("company_id")." ";
		}
		
		if(get("starttime"))
		{
			$where .=" and staff_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime"))
		{
			$where .=" and staff_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("staff")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ts_user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("staff")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	function staff_select_all_nopage_pro($bigwhere="",$limit=999999, $sort="") 
	{
		
		$where = " 1 ";

		$data=M("staff")->where($where.$bigwhere)->order($sort)->limit($limit)->select();

		return $data;
	}

	

}
?>