<?php
/**
 *    #Case		bwvip
 *    #Page		fenzhanModel.class.php (分站)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class fenzhanModel extends Model{

	//list and page
	function fenzhan_list_pro($bigwhere="", $page_size=20, $sort=" addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and fenzhan_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and fenzhan_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("fenzhan","pre_")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("fenzhan","pre_")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function fenzhan_select_pro($bigwhere="",$limit=999999, $sort=" addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("fenzhan","pre_")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("fenzhan","pre_")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>