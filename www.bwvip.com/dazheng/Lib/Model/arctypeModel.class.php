<?php
/**
 *    #Case		tankuang
 *    #Page		arctypeModel.class.php (栏目)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class arctypeModel extends Model{

	//list and page- -- admin  tree for select
	function arctype_admin_tree_pro($bigwhere="",$big_sub="", $page_size=9999, $sort=" arctype_sort asc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";
		
		$data["item"]=M("arctype")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			$sub=M()->query("select * from tbl_arctype where 1 ".$big_sub." and arctype_parent_id='".$data['item'][$i]['arctype_id']."' ");
			$data["item"][$i]['sub']=$sub;
		}
		$data["total"] = M("arctype")->where($where.$bigwhere)->count();

		return $data;
	}

	//list and page- -- admin
	function arctype_admin_list_pro($bigwhere="", $page_size=9999, $sort=" arctype_sort asc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		$data["item"]=M("arctype")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
			$sub=M()->query("select * from tbl_arctype where arctype_parent_id='".$data['item'][$i]['arctype_id']."' ");
			$data["item"][$i]['sub']=$sub;
		}
		$data["total"] = M("arctype")->where($where.$bigwhere)->count();

		return $data;
	}
	
	
	//list and page
	function arctype_list_pro($bigwhere="", $page_size=20, $sort=" arctype_sort asc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and arctype_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and arctype_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("arctype")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("arctype")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function arctype_select_pro($bigwhere="",$limit=999999, $sort=" arctype_sort asc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("arctype")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("arctype")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>