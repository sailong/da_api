<?php
/**
 *    #Case		bwvip
 *    #Page		field_event_rankModel.class.php (球场比赛排名)
 *
 *    @author		Jack
 *    @E-mail		123695069@qq.com
 */
class field_event_rankModel extends Model{

	//list and page
	function field_event_rank_list_pro($bigwhere="", $page_size=20, $sort=" field_event_rank_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("starttime")!="")
		{
			$where .=" and field_event_rank_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$where .=" and field_event_rank_addtime<".strtotime(get("endtime"))." ";
		}

		$data["item"]=M("field_event_rank")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["user_id"]!="")
			{
				$user=M()->query("select uname from ".C("db_prefix")."user where  uid='".$data["item"][$i]["user_id"]."' ");
				$data["item"][$i]["uname"]=$user[0]["uname"];
			}
		}
		$data["total"] = M("field_event_rank")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function field_event_rank_select_pro($bigwhere="",$limit=999999, $sort=" field_event_rank_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("field_event_rank")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("field_event_rank")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>