<?php
/**
 *    #Case		tankuang
 *    #Page		userModel.class.php (用户)
 *
 *    @author		Jack
 *    @E-mail		68779953@qq.com
 */
class userModel extends Model{

	//list and page
	function user_list_pro($bigwhere="", $page_size=20, $sort=" user_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and user_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and user_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("user")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("user")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function user_select_pro($bigwhere="",$limit=999999, $sort=" user_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("user")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("user")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>