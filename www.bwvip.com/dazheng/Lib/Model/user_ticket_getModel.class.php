<?php
/**
 *    #Case		bwvip
 *    #Page		User_ticketAction.class.php (门票领取)
 *
 *    @Author		Zhang Long
 *    @E-mail			123695069@qq.com
 *    @Date			2013-08-06
 */
class user_ticket_getModel extends Model{

	//list and page
	function user_ticket_get_list_pro($bigwhere="", $page_size=20, $sort=" bwm_addtime desc ") 
	{
		$page = intval(get("p"))?get("p"):1;

		$where = " 1 ";

		if(get("k")!="")
		{
			$where .=" and id=".get("k");
		}

		if(get("starttime")!="")
		{
			$where .=" and bwm_addtime>".strtotime(get("starttime"))." ";
		}
		if(get("endtime")!="")
		{
			$endtime = strtotime(get("endtime")) + 86400;
			$where .=" and bwm_addtime<".$endtime." ";
		}
		
		$data["item"]=M("user_ticket_get")->where($where.$bigwhere)->order($sort)->page($page.",".$page_size)->select();
		//echo M()->getLastSql();
		for($i=0; $i<count($data["item"]); $i++)
		{
			if($data["item"][$i]["event_id"]!="")
			{
				$user=M()->query("select event_name from ".C("db_prefix")."event where  event_id='".$data["item"][$i]["event_id"]."' ");
				$data["item"][$i]["event_name"]=$user[0]["event_name"];
			}
		}
		$data["total"] = M("user_ticket_get")->where($where.$bigwhere)->count();
		
		import ("@.ORG.Page");
		$page = new page ($data["total"], $page_size );
		$data["pages"] = $page->show();

		return $data;
	}


	//nopage select limit 
	function user_ticket_get_select_pro($bigwhere="",$limit=999999, $sort=" bwm_addtime desc ") 
	{
		
		$where = " 1 ";

		$data["item"]=M("user_ticket_get")->where($where.$bigwhere)->order($sort)->limit($limit)->select();
		$data["total"]=M("user_ticket_get")->where($where.$bigwhere)->count();

		return $data;
	}


	
	

}
?>