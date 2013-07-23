<?php
/**
 *    #Case		bwvip
 *    #Page		rule_tagModel.class.php (规则江湖)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class rule_tagModel extends Model{

	//list and page
	function rule_tag_list_pro($bigwhere="", $page_size=20, $sort=" rule_tag_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and rule_tag_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and rule_tag_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("rule_tag")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("rule_tag")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function rule_tag_select_pro($bigwhere="",$limit=999999, $sort=" rule_tag_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("rule_tag")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("rule_tag")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>